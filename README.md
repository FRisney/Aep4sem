# AEP 4 Semestre - ODS

Projeto de gerenciamento de ocorrencias de maus-tratos aos animais e doações de animais.

### Dependencias

* gnu make
* docker
* docker-compose
* php 8.0
* composer (php)

### Deploy
Com o repo clonado, para instalar os pacotes do php e as imagens docker, assim como subir os containers, e instalar as dependencias, basta executar:
```
make init
```
Para finalizar os containers:
```
make down
```
Para somente iniciar os containers:
```
make up
```
Para ver o ip dos containers:
```
make ips
```

