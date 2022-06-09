CREATE TABLE ms_user
(
    user_id             INT(2) NOT NULL PRIMARY KEY,
    username            varchar(16),
    password            varchar(32),
    -- mandatory
    is_active           INT(2),
    create_by           INT(2),
    create_at           DATETIME,
    update_by           INT(2),
    update_at           DATETIME
);

CREATE TABLE ms_customer
(
    customer_id             varchar(16) NOT NULL PRIMARY KEY,
    customer_name           varchar(128),
    customer_id_number      varchar(16),
    customer_address        varchar(512),
    customer_proffesion     varchar(128),
    customer_phone          varchar(128),
    customer_collect        INT(2),
    customer_dpd            INT(2),
    customer_active_loan    INT(2),
    is_blacklist            INT(2),
    is_alert                INT(2),
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);

CREATE TABLE ms_reminder
(
    id                      INT(8) NOT NULL PRIMARY KEY,
    customer_id             varchar(16),
    reminder_file_name      varchar(128),
    reminder_generated_date DATETIME,
    reminder_file_path      TEXT,
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);

CREATE TABLE ms_loan
(
    id                      INT(8) NOT NULL PRIMARY KEY,
    loan_number             varchar(16),
    loan_amount             float(?),
    interest_rate           float(?),
    provision_fee           float(?),
    disbursement_amount     float(?),
    tenor                   INT(8),
    installment_amount      float(?),
    collateral_category     TEXT,
    collateral_file_name    TEXT,
    collateral_file_path    TEXT,
    collateral_description  TEXT,
    loan_collect            INT(8),
    loan_dpd                INT(8),
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);

CREATE TABLE tx_incoming
(
    id                      INT(8) NOT NULL PRIMARY KEY,
    loan_id                 INT(8),
    incoming_category       varchar(64),
    incoming_date           DATETIME,
    incoming_amount         float(?),
    notes                   TEXT,
    loan_due_date           DATETIME,
    loan_status             varchar(64),
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);

CREATE TABLE tx_outgoing
(
    id                      INT(8) NOT NULL PRIMARY KEY,
    loan_id                 INT(8),
    outgoing_category       varchar(64),
    outgoing_date           DATETIME,
    outgoing_amount         float(?),
    notes                   TEXT,
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);

CREATE TABLE trx_account_mgmt
(
    id                      INT(8) NOT NULL PRIMARY KEY,
    incoming_id             INT(8),
    outgoing_id             INT(8),
    trx_category            varchar(64),
    trx_amount              float(?),
    notes                   TEXT,
    -- mandatory
    is_active               INT(2),
    create_by               INT(2),
    create_at               DATETIME,
    update_by               INT(2),
    update_at               DATETIME
);