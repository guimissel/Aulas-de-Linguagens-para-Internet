-- PAPEIS
INSERT INTO papel (id_papel, nome) VALUES
	(1, 'admin'),
	(2, 'cliente');

-- ADMIN
INSERT INTO usuario (nome, email, senha, id_papel) VALUES
	('Admin', 'admin@email.com', '$2a$10$8J1KAogXZf99/n.bi0grjuiKYkM3I7oK8pTqOd/Od3QcXakbw1hHa', 1);

-- STATUS
INSERT INTO status (id_status, nome) VALUES
	(1, 'Aguardando pagamento'),
	(2, 'Pagamento confirmado'),
	(3, 'Em separação'),
	(4, 'Enviado'),
	(5, 'Entregue'),
	(6, 'Cancelado');

-- FORMA_PAGAMENTO
-- Catálogo fixo de métodos aceitos
INSERT INTO forma_pagamento (nome, ativo) VALUES
	('Pix', TRUE),
	('Boleto', TRUE),
	('Cartão de Crédito', TRUE),
	('Cartão de Débito', TRUE);

-- ATRIBUTO
-- Tipos de atributo que as variantes podem ter (Cor, Tamanho...).
INSERT INTO atributo (nome) VALUES
	('Cor'),
	('Tamanho');

-- CATEGORIA
-- Categorias base de produto
INSERT INTO categoria (nome, cor) VALUES
	('Roupas', '#3498db'),
	('Calçados', '#e67e22'),
	('Acessórios', '#9b59b6');