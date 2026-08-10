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