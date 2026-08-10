DROP TABLE IF EXISTS variante_pedido;
DROP TABLE IF EXISTS pagamento;
DROP TABLE IF EXISTS pedido;
DROP TABLE IF EXISTS variante_atributo;
DROP TABLE IF EXISTS variante_imagem;
DROP TABLE IF EXISTS variante;
DROP TABLE IF EXISTS produto_categoria;
DROP TABLE IF EXISTS status;
DROP TABLE IF EXISTS atributo;
DROP TABLE IF EXISTS produto;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS endereco_cliente;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS forma_pagamento;
DROP TABLE IF EXISTS endereco;

CREATE TABLE endereco(
	id_endereco INT AUTO_INCREMENT PRIMARY KEY,
	rua VARCHAR(255) NOT NULL,
	numero VARCHAR(20) NOT NULL,
	complemento VARCHAR(255),
	bairro VARCHAR(255),
	cidade VARCHAR(255),
	estado CHAR(2) NOT NULL,
	cep CHAR(8) NOT NULL
);

CREATE TABLE forma_pagamento(
	id_forma_pagamento INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(255) NOT NULL,
	ativo boolean DEFAULT TRUE NOT NULL
);

CREATE TABLE cliente(
	id_cliente INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(255) NOT NULL,
	email VARCHAR(255) UNIQUE NOT NULL,
	senha VARCHAR(255) NOT NULL,
	telefone VARCHAR(20)
);

CREATE TABLE endereco_cliente(
	id_endereco INT NOT NULL,
	id_cliente INT NOT NULL,
	
	PRIMARY KEY (id_endereco, id_cliente),
	
	FOREIGN KEY (id_endereco) REFERENCES endereco(id_endereco)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE categoria(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(255) NOT NULL,
	cor VARCHAR(7) DEFAULT '#ffffff'
);

CREATE TABLE produto(
	id_produto INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(255) NOT NULL,
	descricao TEXT,
	ativo BOOLEAN DEFAULT TRUE NOT NULL
);

CREATE TABLE atributo(
	id_atributo INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(255) NOT NULL
);

CREATE TABLE status(
	id_status INT AUTO_INCREMENT PRIMARY KEY,
	nome varchar(255) NOT NULL
);

CREATE TABLE produto_categoria(
	id_produto INT,
	id_categoria INT,
	
	PRIMARY KEY (id_produto, id_categoria),
	
	FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE variante(
	id_variante INT AUTO_INCREMENT PRIMARY KEY,
	preco DECIMAL(10,2) NOT NULL CHECK(preco > 0),
	sku varchar(255) UNIQUE NOT NULL,
	estoque INT DEFAULT 0 NOT NULL CHECK(estoque >= 0),
	ativo BOOLEAN DEFAULT TRUE NOT NULL,
	id_produto INT NOT NULL,
	
	FOREIGN KEY (id_produto) REFERENCES produto(id_produto)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE variante_imagem(
	id_variante_imagem INT AUTO_INCREMENT PRIMARY KEY,
	url VARCHAR(255) NOT NULL,
	ordem INT DEFAULT 0,
	id_variante INT NOT NULL,
	
	FOREIGN KEY (id_variante) REFERENCES variante(id_variante)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE variante_atributo(
	id_variante INT NOT NULL,
	id_atributo INT NOT NULL,
	valor varchar(255),
	
	PRIMARY KEY (id_variante, id_atributo),
	
	FOREIGN KEY (id_variante) REFERENCES variante(id_variante)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (id_atributo) REFERENCES atributo(id_atributo)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE pedido(
	id_pedido INT AUTO_INCREMENT PRIMARY KEY,
	data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
	rua VARCHAR(255) NOT NULL,
	numero VARCHAR(20) NOT NULL,
	complemento VARCHAR(255),
	bairro VARCHAR(255),
	cidade VARCHAR(255),
	estado CHAR(2) NOT NULL,
	cep CHAR(8) NOT NULL,
	id_cliente INT NOT NULL,
	id_status INT DEFAULT 1, -- Aguardando pagamento
	
	FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
		ON DELETE RESTRICT
		ON UPDATE CASCADE,
	FOREIGN KEY (id_status) REFERENCES status(id_status)
		ON DELETE SET NULL
		ON UPDATE CASCADE
);

CREATE TABLE pagamento(
	id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
	valor DECIMAL(10,2) NOT NULL,
	data_pagamento DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
	confirmado boolean DEFAULT FALSE NOT NULL,
	id_pedido INT NOT NULL,
	id_forma_pagamento INT NOT NULL,
	
	FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
		ON DELETE RESTRICT
		ON UPDATE CASCADE,
	FOREIGN KEY (id_forma_pagamento) REFERENCES forma_pagamento(id_forma_pagamento)
		ON DELETE RESTRICT
		ON UPDATE CASCADE
);

CREATE TABLE variante_pedido(
	id_variante INT NOT NULL,
	id_pedido INT NOT NULL,
	quantidade INT NOT NULL,
	preco DECIMAL(10,2) NOT NULL,
	
	PRIMARY KEY (id_variante, id_pedido),
	
	FOREIGN KEY (id_variante) REFERENCES variante(id_variante)
		ON DELETE RESTRICT
		ON UPDATE CASCADE,
	FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
		ON DELETE RESTRICT
		ON UPDATE CASCADE
);