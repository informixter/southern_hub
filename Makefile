API_CONTAINER_NAME=api-image-shiva
FRONTEND_IMAGE_NAME=frontend-shiva
build_auto_ml:
	cd auto-ml && docker build -t auto-ml .

build_api:
	cd api && docker build -t $(API_CONTAINER_NAME) -f ./Dockerfile .

build_front_end:
	cd frontend && docker build --rm -t $(FRONTEND_IMAGE_NAME) .

build: build_api build_auto_ml build_front_end

run:
	docker-compose up -d nginx backend_shiva postgres auto-ml

down:
	docker-compose down

download:
	curl -o ./auto-ml/gensim-data/word2vec-ruscorpora-300/word2vec-ruscorpora-300.gz https://59830.selcdn.ru/insrt.ru/word2vec-ruscorpora-300.gz