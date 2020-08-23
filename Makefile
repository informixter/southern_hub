API_CONTAINER_NAME=api-image

build_auto_ml:
	cd auto-ml && docker build -t auto-ml .

build_api:
	cd api && docker build -t $(API_CONTAINER_NAME) -f ./Dockerfile .
