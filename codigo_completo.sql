-- Tipo enumerado usado na tabela veiculos
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'tipo_veiculo') THEN
        CREATE TYPE tipo_veiculo AS ENUM ('OFICIAL', 'PARTICULAR', 'MOTO');
    END IF;
END$$;

-- Tabela perfis
CREATE TABLE perfis (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- Tabela permissoes
CREATE TABLE permissoes (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT
);

-- Tabela perfil_permissoes (join many-to-many)
CREATE TABLE perfil_permissoes (
    perfil_id INT NOT NULL,
    permissao_id INT NOT NULL,
    PRIMARY KEY (perfil_id, permissao_id),
    FOREIGN KEY (perfil_id) REFERENCES perfis(id),
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id)
);

-- Tabela usuarios
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    matricula VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil_id INT NOT NULL,
    FOREIGN KEY (perfil_id) REFERENCES perfis(id)
);

-- Tabela acessos_liberados
CREATE TABLE acessos_liberados (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    matricula VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela motoristas_oficiais
CREATE TABLE motoristas_oficiais (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela veiculos
CREATE TABLE veiculos (
    id SERIAL PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,
    modelo VARCHAR(100),
    tipo tipo_veiculo NOT NULL,
    motorista_id INTEGER,
    acesso_id INTEGER,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    acesso_liberado_id BIGINT,
    CONSTRAINT veiculos_motorista_id_fkey FOREIGN KEY (motorista_id) REFERENCES motoristas_oficiais(id),
    CONSTRAINT veiculos_acesso_id_fkey FOREIGN KEY (acesso_id) REFERENCES acessos_liberados(id),
    CONSTRAINT fk_veiculos_acesso_liberado FOREIGN KEY (acesso_liberado_id) REFERENCES acessos_liberados(id) ON DELETE SET NULL
);

-- Tabela estacionamentos
CREATE TABLE estacionamentos (
    id SERIAL PRIMARY KEY,
    capacidade INTEGER NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela registro_veiculos
CREATE TABLE registro_veiculos (
    id SERIAL PRIMARY KEY,
    veiculo_id INTEGER,
    estacionamento_id INTEGER,
    hora_entrada TIMESTAMP,
    hora_saida TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT registro_veiculos_veiculo_id_fkey FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    CONSTRAINT registro_veiculos_estacionamento_id_fkey FOREIGN KEY (estacionamento_id) REFERENCES estacionamentos(id)
);

-- Tabela ocorrencias
CREATE TABLE ocorrencias (
    id SERIAL PRIMARY KEY,
    registro_id INTEGER,
    descricao TEXT,
    data TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT ocorrencias_registro_id_fkey FOREIGN KEY (registro_id) REFERENCES registro_veiculos(id)
);

-- Tabela sessions 
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

-- Índices extras nas sessions
CREATE INDEX sessions_last_activity_index ON sessions(last_activity);
CREATE INDEX sessions_user_id_index ON sessions(user_id);

