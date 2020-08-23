API_CONTAINER_NAME=api-image-shiva
FRONTEND_IMAGE_NAME=frontend-shiva
build_auto_ml:
	cd auto-ml && docker build -t auto-ml .

build_api:
	cd api && docker build -t $(API_CONTAINER_NAME) -f ./Dockerfile .

build_front_end:
	cd frontend && docker build --rm -t $(FRONTEND_IMAGE_NAME) .

build: build_api build_front_end

run:
	docker-compose up -d nginx backend_shiva postgres auto-ml

stop:
	docker-compose down

download:
	curl -o ./auto-ml/gensim-data/word2vec-ruscorpora-300/word2vec-ruscorpora-300.gz https://59830.selcdn.ru/insrt.ru/word2vec-ruscorpora-300.gz

install: download build install_data

install_data:
	docker-compose up -d backend_shiva
	sleep 10
	docker-compose exec backend_shiva bash -c "cd backend && chmod -R 0777 ./ && composer install && php artisan migrate && php artisan db:seed"
	make stop