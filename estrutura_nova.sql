-- Criar ENUM de tipo de veículo (sem IF NOT EXISTS por compatibilidade)
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'tipo_veiculo') THEN
        CREATE TYPE tipo_veiculo AS ENUM ('OFICIAL', 'VISITANTE', 'TERCEIRO');
    END IF;
END$$;

-- Tabela acessos_liberados
CREATE TABLE acessos_liberados (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    matricula VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- Tabela motoristas_oficiais
CREATE TABLE motoristas_oficiais (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- Tabela veiculos
CREATE TABLE veiculos (
    id SERIAL PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,
    modelo VARCHAR(100),
    tipo tipo_veiculo NOT NULL,
    motorista_id INTEGER REFERENCES motoristas_oficiais(id),
    acesso_id INTEGER REFERENCES acessos_liberados(id),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- Tabela estacionamentos
CREATE TABLE estacionamentos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    capacidade INTEGER NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- Tabela registro_veiculos
CREATE TABLE registro_veiculos (
    id SERIAL PRIMARY KEY,
    veiculo_id INTEGER REFERENCES veiculos(id),
    estacionamento_id INTEGER REFERENCES estacionamentos(id),
    hora_entrada TIMESTAMP,
    hora_saida TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);

-- Tabela ocorrencias
CREATE TABLE ocorrencias (
    id SERIAL PRIMARY KEY,
    registro_id INTEGER REFERENCES registro_veiculos(id),
    descricao TEXT,
    data TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL
);
