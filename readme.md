
# Montink Test

Este projeto é uma aplicação PHP com Apache e MySQL, que também utiliza um servidor de socket. Ele pode ser executado facilmente via Docker ou manualmente.

## 📦 Requisitos

### ✅ Com Docker

* Docker
* Docker Compose

### ✅ Sem Docker

* PHP 8.1 (limite para utilizar o codeigniter 3)
* Apache 2.4+
* MySQL 5.7
* Node.js (para o socket server)
* Composer (para dependências PHP, se houver)
* npm/yarn (para dependências do socket)

## 🚀 Como rodar com Docker

### 1. Clone o repositório

```bash
git clone https://github.com/fdanielarruda/gerenciar-produtos.git
cd gerenciar-produtos
```

### 2. Crie um arquivo `.env`

Crie um arquivo `.env` na raiz com as variáveis necessárias:

```env
# Banco de dados
DB_ROOT_USER=montink_root
DB_ROOT_PASSWORD=sua_senha
DB_NAME=montink_db
DB_PORT=3307

# App principal
APP_PORT=8080

# Servidor de Socket
SOCKET_PORT=3000
```

### 3. Suba os containers

```bash
docker-compose up --build -d
```

A aplicação estará disponível em: [http://localhost:8080](http://localhost:8080)

### 4. Acesso ao banco de dados

* Host: `localhost`
* Porta: definido em `DB_PORT`
* Usuário: definido em `DB_ROOT_USER`
* Senha: definida na variável `DB_ROOT_PASSWORD`
* Banco: definido em `DB_NAME`

---

## 💻 Como rodar sem Docker

### 1. Instale e configure o ambiente manualmente

* Apache com suporte a `.htaccess` e `mod_rewrite`
* PHP 8.1 (limite da versão 3 do codeigniter) com extensão `mysqli` e `phpcurl`
* MySQL 5.7 com um banco de dados criado

### 2. Configure o Apache

Certifique-se de que o módulo `rewrite` está habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

No arquivo de configuração do Apache ou no `.htaccess`, certifique-se que o `AllowOverride` está habilitado para o diretório do projeto.

### 3. Copie os arquivos do projeto para a pasta do servidor

```bash
sudo cp -r . /var/www/html/montink
sudo chown -R www-data:www-data /var/www/html/montink
sudo chmod -R 755 /var/www/html/montink
```

### 4. Crie um banco de dados

Crie um banco com o nome que desejar e configure a conexão no seu código PHP.

### 5. Suba o socket server

Vá para a pasta `socket-server`:

```bash
cd socket-server
npm install
npm start
```

Por padrão, ele rodará em: [http://localhost:3000](http://localhost:3000)


