from japronto import Application

def echo_service(request):
    return request.Response(json={"status": "ok"})


app = Application()
app.router.add_route('/', echo_service)
app.run(debug=True)