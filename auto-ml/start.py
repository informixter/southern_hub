from japronto import Application
import gensim.downloader as api
import pymorphy2
import tensorflow as tf
import autokeras as ak
import joblib


morph = pymorphy2.MorphAnalyzer()
ttws = tf.keras.preprocessing.text.text_to_word_sequence
import numpy as np
from sklearn.model_selection import train_test_split
import psycopg2

print("Start")

word2vec_model300 = fasttext_model300 = api.load('word2vec-ruscorpora-300')

print("finish warminup")
sequence_len = 20


def wordToVec(text):
    try:
        m = morph.parse(text)[0]
        wordIndex = "{word}_{pos}".format(word=m.word, pos=m.normalized.tag.POS)
        return word2vec_model300.word_vec(wordIndex)
    except:
        return []


def sequence2vec(text):
    spl = ttws(text, filters='!"#$%&()*+,-./:;<=>?@[\\]^_`{|}~\t\n', lower=True, split=' ')
    res = np.zeros((sequence_len, 300))
    i = 0
    for w in spl:
        if i > sequence_len:
            break
        v = wordToVec(w)
        if len(v) > 0:
            res[i] = np.array([v])
            i += 1
    return res


def predict_model(request):
    conn = psycopg2.connect(dbname='laravel', user='default',
                            password='secret', host='postgres')
    cursor = conn.cursor()
    cursor.execute("select name from labels where model_id = {} order by id".format(request.match_dict['model']))
    labels_rows = cursor.fetchall()
    labels = []
    for l in labels_rows:
        labels.append(l[0])

    X = np.zeros((1, sequence_len, 300))
    X[0]=sequence2vec(request.json['text'])

    filename = 'models/model_{}.pkl'.format(request.match_dict['model'])
    auto_model = joblib.load(filename)
    cursor.close()
    conn.close()
    res = auto_model.predict(X)
    result = []
    i = 0
    for r in res[0]:
        if r > 0:
            result.append(labels[i])
        i += 1

    return request.Response(json=result)

def train_model(request):
    conn = psycopg2.connect(dbname='laravel', user='default',
                            password='secret', host='postgres')
    cursor = conn.cursor()

    cursor.execute("select name from labels where model_id = {} order by id".format(request.match_dict['model']))
    labels_rows = cursor.fetchall()
    labels = []
    for l in labels_rows:
        labels.append(l[0])

    sql = "select text, to_jsonb(array (select (select name from labels where labels.id = labels_texts.model_id) from labels_texts where labels_texts.text_id = texts.id)) from texts where model_id = {}".format(
        request.match_dict['model'])
    cursor.execute(sql)
    records = cursor.fetchall()

    values = []
    pre_y = []
    i = 0

    for row in records:
        if len(row[1]) > 0:
            values.append(row[0])
            pre_y.append(row[1])
        i += 1

    cursor.close()
    conn.close()

    X = np.zeros((len(values), sequence_len, 300))
    y = np.zeros((len(values), len(labels)))
    print(pre_y)
    for i in range(0, len(values)):
        X[i] = sequence2vec(values[i])
        ty = []
        for yy in labels:
            set = 0
            if yy in pre_y[i]:
                set = 1
            ty.append(set)
        y[i] = ty
        print(y[i])

    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.33, random_state=42)

    inputs = ak.Input()
    output = ak.ClassificationHead(multi_label=True)(inputs)

    auto_model = ak.AutoModel(inputs=[inputs],
                              overwrite=True,
                              outputs=[output],
                              max_trials=10)

    auto_model.fit(X_train, y_train, epochs=2, batch_size=1)

    joblib.dump(auto_model, 'models/model_{}.pkl'.format(request.match_dict['model']))


    return request.Response(json=auto_model.evaluate(X_test, y_test))


def echo_service(request):
    return request.Response(json={"status": "ok"})


app = Application()
app.router.add_route('/predict/{model}', predict_model)
app.router.add_route('/train/{model}', train_model)
app.router.add_route('/', echo_service)
app.run(debug=True)

