
CREATE SEQUENCE minidb.fico_ncoa_id_coa_seq_1;

CREATE TABLE minidb.fico_ncoa (
                id_coa INTEGER NOT NULL DEFAULT nextval('minidb.fico_ncoa_id_coa_seq_1'),
                cdfiacc VARCHAR(12) NOT NULL,
                dscrp VARCHAR NOT NULL,
                dk VARCHAR(2) NOT NULL,
                level SMALLINT NOT NULL,
                begining_balance DOUBLE PRECISION NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                Parent_id_coa INTEGER NOT NULL,
                CONSTRAINT fico_ncoa_pk PRIMARY KEY (id_coa)
);
COMMENT ON TABLE minidb.fico_ncoa IS 'new coa table';
COMMENT ON COLUMN minidb.fico_ncoa.dk IS 'saldonormal';


ALTER SEQUENCE minidb.fico_ncoa_id_coa_seq_1 OWNED BY minidb.fico_ncoa.id_coa;

CREATE SEQUENCE minidb.fico_periode_id_periode_seq_1_3;

CREATE TABLE minidb.fico_periode (
                id_periode INTEGER NOT NULL DEFAULT nextval('minidb.fico_periode_id_periode_seq_1_3'),
                nmperiode VARCHAR NOT NULL,
                tahun INTEGER NOT NULL,
                date_fr DATE NOT NULL,
                date_to DATE NOT NULL,
                is_active SMALLINT NOT NULL,
                update_date TIMESTAMP,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                create_by INTEGER DEFAULT 0,
                CONSTRAINT fico_periode_pk PRIMARY KEY (id_periode)
);


ALTER SEQUENCE minidb.fico_periode_id_periode_seq_1_3 OWNED BY minidb.fico_periode.id_periode;

CREATE TABLE minidb.fico_balancesheet (
                id_coa INTEGER NOT NULL,
                id_periode INTEGER NOT NULL,
                begining_balance DOUBLE PRECISION NOT NULL,
                debit DOUBLE PRECISION NOT NULL,
                kredit DOUBLE PRECISION NOT NULL,
                ending_balance DOUBLE PRECISION NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT balancesheet_pk PRIMARY KEY (id_coa, id_periode)
);
COMMENT ON TABLE minidb.fico_balancesheet IS 'balance sheet record from begining periode';


CREATE SEQUENCE minidb.sys_vlookup_idlook_seq;

CREATE TABLE minidb.sys_vlookup (
                idlook INTEGER NOT NULL DEFAULT nextval('minidb.sys_vlookup_idlook_seq'),
                groupv VARCHAR(64) NOT NULL,
                cdlookup VARCHAR(16) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT sys_vlookup_pk PRIMARY KEY (idlook)
);


ALTER SEQUENCE minidb.sys_vlookup_idlook_seq OWNED BY minidb.sys_vlookup.idlook;

CREATE SEQUENCE minidb.tbl_user_id_seq;

CREATE TABLE minidb.tbl_user (
                id INTEGER NOT NULL DEFAULT nextval('minidb.tbl_user_id_seq'),
                username VARCHAR(128) NOT NULL,
                password VARCHAR(128) NOT NULL,
                salt VARCHAR(128) NOT NULL,
                email VARCHAR(128) NOT NULL,
                profile VARCHAR(256) NOT NULL,
                access VARCHAR,
                CONSTRAINT tbl_user_pkey PRIMARY KEY (id)
);
COMMENT ON COLUMN minidb.tbl_user.access IS 'action list, separated by semicolon';


ALTER SEQUENCE minidb.tbl_user_id_seq OWNED BY minidb.tbl_user.id;

CREATE TABLE minidb.sys_numgen (
                cdnumgen VARCHAR(13) NOT NULL,
                prefix VARCHAR(8) NOT NULL,
                pattern VARCHAR NOT NULL,
                startnum VARCHAR(13) NOT NULL,
                year VARCHAR(2) NOT NULL,
                date DATE,
                last_value VARCHAR,
                dscrp VARCHAR(32) NOT NULL,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT sys_numgen_pk PRIMARY KEY (cdnumgen)
);


CREATE TABLE minidb.sys_conf (
                cdsysconf VARCHAR(13) NOT NULL,
                dscrp VARCHAR(32) NOT NULL,
                last_value VARCHAR,
                curnt_value VARCHAR,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT sys_conf_pk PRIMARY KEY (cdsysconf)
);


CREATE TABLE minidb.sys_org (
                cdorg VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT sys_org_pk PRIMARY KEY (cdorg)
);


CREATE TABLE minidb.sys_unit (
                cdunit VARCHAR(13) NOT NULL,
                cdorg VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT sys_unit_pk PRIMARY KEY (cdunit)
);


CREATE TABLE minidb.tbl_userunit (
                id INTEGER NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                update_by INTEGER,
                dscrp VARCHAR NOT NULL,
                create_date TIMESTAMP DEFAULT now(),
                create_by INTEGER DEFAULT 0,
                update_date TIMESTAMP,
                is_default BOOLEAN DEFAULT false NOT NULL,
                CONSTRAINT tbl_userunit_pk PRIMARY KEY (id, cdunit)
);


CREATE TABLE minidb.fico_gl (
                cdfigl VARCHAR NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                id_periode INTEGER NOT NULL,
                gl_date DATE DEFAULT now() NOT NULL,
                refnum VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_date TIMESTAMP,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                create_by INTEGER DEFAULT 0,
                CONSTRAINT fico_gl_pk PRIMARY KEY (cdfigl)
);


CREATE SEQUENCE minidb.fico_gldtl_idgldtl_seq;

CREATE TABLE minidb.fico_gldtl (
                idgldtl VARCHAR NOT NULL DEFAULT nextval('minidb.fico_gldtl_idgldtl_seq'),
                cdfigl VARCHAR NOT NULL,
                id_coa INTEGER NOT NULL,
                debit DOUBLE PRECISION DEFAULT 0 NOT NULL,
                kredit DOUBLE PRECISION DEFAULT 0 NOT NULL,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT fico_gldtl_pk PRIMARY KEY (idgldtl)
);


ALTER SEQUENCE minidb.fico_gldtl_idgldtl_seq OWNED BY minidb.fico_gldtl.idgldtl;

CREATE TABLE minidb.inv_warehouse (
                cdwhse VARCHAR(13) NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT inv_warehouse_pk PRIMARY KEY (cdwhse)
);
COMMENT ON TABLE minidb.inv_warehouse IS 'nama warehouse';


CREATE TABLE minidb.invgr_hdr (
                gr_num VARCHAR(13) NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                id_periode INTEGER NOT NULL,
                date_gr DATE NOT NULL,
                refnum VARCHAR(32) NOT NULL,
                create_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT invgr_hdr_pk PRIMARY KEY (gr_num)
);


CREATE TABLE minidb.invgi_hdr (
                gi_num VARCHAR(13) NOT NULL,
                id_periode INTEGER NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                refnum VARCHAR(32) NOT NULL,
                date_gi DATE NOT NULL,
                create_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT invgi_hdr_pk PRIMARY KEY (gi_num)
);


CREATE TABLE minidb.invtrf_hdr (
                trf_num VARCHAR(13) NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                cdwhse2 VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                date_trf DATE NOT NULL,
                status VARCHAR,
                gi_num VARCHAR(13) NOT NULL,
                gr_num VARCHAR(13) NOT NULL,
                create_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT invtrf_hdr_pk PRIMARY KEY (trf_num)
);


CREATE TABLE minidb.inv_locator (
                cdloct VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                cdwhse VARCHAR(13) NOT NULL,
                CONSTRAINT inv_locator_pk PRIMARY KEY (cdloct)
);
COMMENT ON TABLE minidb.inv_locator IS 'nama locator';


CREATE TABLE minidb.invm2m_hdr (
                m2m_num VARCHAR(13) NOT NULL,
                refnum VARCHAR(32) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                date_m2m DATE NOT NULL,
                create_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                cdunit VARCHAR(13) NOT NULL,
                CONSTRAINT invm2m_hdr_pk PRIMARY KEY (m2m_num)
);


CREATE TABLE minidb.mdprice_cat (
                cdpcat VARCHAR(13) NOT NULL,
                dscrp VARCHAR(32) NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER,
                CONSTRAINT mdprice_cat_pk PRIMARY KEY (cdpcat)
);


CREATE TABLE minidb.mdvendor_cat (
                cdvendcat VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mdvendorcat__pk PRIMARY KEY (cdvendcat)
);
COMMENT ON TABLE minidb.mdvendor_cat IS 'partner_group: resales, customer, supplier, dll';


CREATE TABLE minidb.mdvendor (
                cdvend VARCHAR(13) NOT NULL,
                cdvendcat VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                contact VARCHAR,
                phone VARCHAR(14),
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT mdvendor_pk PRIMARY KEY (cdvend)
);


CREATE TABLE minidb.sales_hdr (
                sal_num VARCHAR NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                cdvend VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                status VARCHAR(4) NOT NULL,
                date_sales DATE NOT NULL,
                sal_type VARCHAR NOT NULL,
                id_periode INTEGER NOT NULL,
                create_date TIMESTAMP DEFAULT now(),
                create_by INTEGER,
                update_date TIMESTAMP,
                update_by INTEGER,
                CONSTRAINT sales_hdr_pk PRIMARY KEY (sal_num)
);
COMMENT ON COLUMN minidb.sales_hdr.sal_type IS 'cash or credit';


CREATE TABLE minidb.invpurch_hdr (
                purch_num VARCHAR(13) NOT NULL,
                cdvend VARCHAR(13) NOT NULL,
                id_periode INTEGER NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                refnum VARCHAR(32) NOT NULL,
                status VARCHAR NOT NULL,
                termofpayment VARCHAR NOT NULL,
                cdunit VARCHAR(13) NOT NULL,
                gr_num VARCHAR(13) NOT NULL,
                bill_num VARCHAR NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                create_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT invpurch_hdr_pk PRIMARY KEY (purch_num)
);


CREATE TABLE minidb.fico_hutang (
                cdvend VARCHAR(13) NOT NULL,
                purch_num VARCHAR(13) NOT NULL,
                total_hutang DOUBLE PRECISION DEFAULT 0 NOT NULL,
                total_bayar DOUBLE PRECISION DEFAULT 0 NOT NULL,
                date_post DATE NOT NULL,
                status INTEGER DEFAULT 0 NOT NULL,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT fico_hutang_pk PRIMARY KEY (cdvend, purch_num)
);


CREATE TABLE minidb.fico_bayar (
                cdvend VARCHAR(13) NOT NULL,
                purch_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cdfigl VARCHAR NOT NULL,
                jml_bayar DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_date TIMESTAMP,
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT fico_bayar_pk PRIMARY KEY (cdvend, purch_num, lnum)
);


CREATE TABLE minidb.mditem_category (
                cdicat VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                update_date TIMESTAMP,
                CONSTRAINT mditem_category_pk PRIMARY KEY (cdicat)
);
COMMENT ON TABLE minidb.mditem_category IS 'category : kaos oblong, polo, alat rumah tangga, dll';


CREATE TABLE minidb.mdprice_formula (
                cdpcat VARCHAR(13) NOT NULL,
                cdicat VARCHAR(13) NOT NULL,
                prsn_margin DOUBLE PRECISION NOT NULL,
                val_margin DOUBLE PRECISION NOT NULL,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mdprice_formula_pk PRIMARY KEY (cdpcat, cdicat)
);


CREATE TABLE minidb.mditem_group (
                cdgroup VARCHAR(13) NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mditem_group_pk PRIMARY KEY (cdgroup)
);
COMMENT ON TABLE minidb.mditem_group IS 'category : finish good, wip, asset, dll';


CREATE TABLE minidb.mditem_uom (
                cduom VARCHAR(13) NOT NULL,
                dscrp VARCHAR(32) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mditem_uom_pk PRIMARY KEY (cduom)
);


CREATE TABLE minidb.mdItem (
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                dscrp VARCHAR(128) NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cdgroup VARCHAR(13) NOT NULL,
                cdicat VARCHAR(13) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mditem_pk PRIMARY KEY (cditem, lnitem)
);
COMMENT ON TABLE minidb.mdItem IS 'Main data master';


CREATE TABLE minidb.sales_dtl (
                sal_num VARCHAR NOT NULL,
                lnum VARCHAR NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                qty DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomdiskon DOUBLE PRECISION NOT NULL,
                create_date TIMESTAMP DEFAULT now(),
                create_by INTEGER DEFAULT 0,
                update_date TIMESTAMP,
                update_by INTEGER,
                CONSTRAINT sales_dtl_pk PRIMARY KEY (sal_num, lnum)
);


CREATE TABLE minidb.invgr_dtl (
                gr_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                qty DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                markup DOUBLE PRECISION NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invgr_dtl_pk PRIMARY KEY (gr_num, lnum)
);


CREATE SEQUENCE minidb.invmv_stock_mvstock_id_seq;

CREATE TABLE minidb.invmv_stock (
                mvstock_id BIGINT NOT NULL DEFAULT nextval('minidb.invmv_stock_mvstock_id_seq'),
                id_periode INTEGER NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cdwhse VARCHAR(13) NOT NULL,
                refnum VARCHAR NOT NULL,
                date_mv TIMESTAMP NOT NULL,
                qtymv DOUBLE PRECISION DEFAULT 0 NOT NULL,
                qtynow DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_by INTEGER,
                create_date TIMESTAMP DEFAULT now(),
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invmv_stock_pk PRIMARY KEY (mvstock_id)
);


ALTER SEQUENCE minidb.invmv_stock_mvstock_id_seq OWNED BY minidb.invmv_stock.mvstock_id;

CREATE TABLE minidb.mduom_conf (
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cduom2 VARCHAR(13) NOT NULL,
                qty DOUBLE PRECISION DEFAULT 1 NOT NULL,
                qty2 DOUBLE PRECISION DEFAULT 1 NOT NULL,
                create_date TIMESTAMP DEFAULT now(),
                update_date TIMESTAMP,
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT mduom_conf_pk PRIMARY KEY (cditem, lnitem, cduom, cduom2)
);
COMMENT ON TABLE minidb.mduom_conf IS 'uom confersion';


CREATE TABLE minidb.invm2m_dtl (
                m2m_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cduom2 VARCHAR(13) NOT NULL,
                qty DOUBLE PRECISION NOT NULL,
                qty2 DOUBLE PRECISION NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                Column_2uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                tuomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invm2m_dtl_pk PRIMARY KEY (m2m_num, lnum)
);


CREATE TABLE minidb.invtrf_dtl (
                trf_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                qtytrf DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invtrf_dtl_pk PRIMARY KEY (trf_num, lnum)
);


CREATE TABLE minidb.invgi_dtl (
                gi_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                qty DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                markup DOUBLE PRECISION NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invgi_dtl_pk PRIMARY KEY (gi_num, lnum)
);


CREATE TABLE minidb.invpurch_dtl (
                purch_num VARCHAR(13) NOT NULL,
                lnum INTEGER NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                qtypurch DOUBLE PRECISION NOT NULL,
                qtygr DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomcost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                uomprice DOUBLE PRECISION DEFAULT 0 NOT NULL,
                markup DOUBLE PRECISION NOT NULL,
                update_date TIMESTAMP,
                create_date TIMESTAMP DEFAULT now(),
                update_by INTEGER,
                create_by INTEGER DEFAULT 0,
                CONSTRAINT invpurch_dtl_pk PRIMARY KEY (purch_num, lnum)
);


CREATE TABLE minidb.mditem_price (
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cduom VARCHAR(13) NOT NULL,
                cdpcat VARCHAR(13) NOT NULL,
                price_comp VARCHAR NOT NULL,
                prsn_price DOUBLE PRECISION NOT NULL,
                val_cost DOUBLE PRECISION DEFAULT 0 NOT NULL,
                val_price DOUBLE PRECISION DEFAULT 0 NOT NULL,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                update_date TIMESTAMP,
                update_by INTEGER,
                CONSTRAINT mditem_price_pk PRIMARY KEY (cditem, lnitem, cduom, cdpcat)
);
COMMENT ON COLUMN minidb.mditem_price.val_cost IS 'generate by moving average';


CREATE TABLE minidb.mditem_vendor (
                cditem VARCHAR(13) NOT NULL,
                lnitem SMALLINT DEFAULT 10 NOT NULL,
                cdvend VARCHAR(13) NOT NULL,
                update_by INTEGER,
                update_date TIMESTAMP,
                create_by INTEGER DEFAULT 0,
                create_date TIMESTAMP DEFAULT now(),
                CONSTRAINT mditem_vendor_pk PRIMARY KEY (cditem, lnitem, cdvend)
);


ALTER TABLE minidb.fico_ncoa ADD CONSTRAINT fico_ncoa_fico_ncoa_fk
FOREIGN KEY (Parent_id_coa)
REFERENCES minidb.fico_ncoa (id_coa)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_gldtl ADD CONSTRAINT fico_ncoa_fico_gldtl_fk
FOREIGN KEY (id_coa)
REFERENCES minidb.fico_ncoa (id_coa)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_balancesheet ADD CONSTRAINT fico_ncoa_fico_balancesheet_fk
FOREIGN KEY (id_coa)
REFERENCES minidb.fico_ncoa (id_coa)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_gl ADD CONSTRAINT fico_periode_fico_gl_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_hdr ADD CONSTRAINT fico_periode_invgr_hdr_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invmv_stock ADD CONSTRAINT fico_periode_invmv_stock_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_hdr ADD CONSTRAINT fico_periode_invpurch_hdr_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_hdr ADD CONSTRAINT fico_periode_invgi_hdr_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_hdr ADD CONSTRAINT fico_periode_sales_hdr_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_balancesheet ADD CONSTRAINT fico_periode_fico_balancesheet_fk
FOREIGN KEY (id_periode)
REFERENCES minidb.fico_periode (id_periode)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.tbl_userunit ADD CONSTRAINT tbl_user_user_to_fk
FOREIGN KEY (id)
REFERENCES minidb.tbl_user (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sys_unit ADD CONSTRAINT sys_org_sys_unit_fk
FOREIGN KEY (cdorg)
REFERENCES minidb.sys_org (cdorg)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.inv_warehouse ADD CONSTRAINT sys_unit_inv_warehouse_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_hdr ADD CONSTRAINT sys_unit_invpurch_hdr_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_hdr ADD CONSTRAINT sys_unit_invgi_hdr_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_hdr ADD CONSTRAINT sys_unit_invmv_hdr_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invm2m_hdr ADD CONSTRAINT sys_unit_invm2m_hdr_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_gl ADD CONSTRAINT sys_unit_fico_gl_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_hdr ADD CONSTRAINT sys_unit_invgi_hdr_1_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.tbl_userunit ADD CONSTRAINT sys_unit_user_to_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_hdr ADD CONSTRAINT sys_unit_sales_hdr_fk
FOREIGN KEY (cdunit)
REFERENCES minidb.sys_unit (cdunit)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_gldtl ADD CONSTRAINT fico_gl_fico_gldtl_fk
FOREIGN KEY (cdfigl)
REFERENCES minidb.fico_gl (cdfigl)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_bayar ADD CONSTRAINT fico_gl_fico_bayar_fk
FOREIGN KEY (cdfigl)
REFERENCES minidb.fico_gl (cdfigl)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invm2m_hdr ADD CONSTRAINT inv_warehouse_invm2m_hdr_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invmv_stock ADD CONSTRAINT inv_warehouse_invmv_stock_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.inv_locator ADD CONSTRAINT inv_warehouse_inv_locator_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_hdr ADD CONSTRAINT inv_warehouse_invtrf_hdr_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_hdr ADD CONSTRAINT inv_warehouse_invtrf_hdr_fk1
FOREIGN KEY (cdwhse2)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_hdr ADD CONSTRAINT inv_warehouse_invgi_hdr_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_hdr ADD CONSTRAINT inv_warehouse_invgi_hdr_1_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_hdr ADD CONSTRAINT inv_warehouse_sales_hdr_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_hdr ADD CONSTRAINT inv_warehouse_invpurch_hdr_fk
FOREIGN KEY (cdwhse)
REFERENCES minidb.inv_warehouse (cdwhse)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_dtl ADD CONSTRAINT invgr_hdr_invgr_dtl_fk1
FOREIGN KEY (gr_num)
REFERENCES minidb.invgr_hdr (gr_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_hdr ADD CONSTRAINT invgr_hdr_invtrf_hdr_fk
FOREIGN KEY (gr_num)
REFERENCES minidb.invgr_hdr (gr_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_dtl ADD CONSTRAINT invgi_hdr_invgi_dtl_fk
FOREIGN KEY (gi_num)
REFERENCES minidb.invgi_hdr (gi_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_hdr ADD CONSTRAINT invgi_hdr_invtrf_hdr_fk
FOREIGN KEY (gi_num)
REFERENCES minidb.invgi_hdr (gi_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_dtl ADD CONSTRAINT invtrf_hdr_invtrf_dtl_fk
FOREIGN KEY (trf_num)
REFERENCES minidb.invtrf_hdr (trf_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invm2m_dtl ADD CONSTRAINT invm2m_hdr_invm2m_dtl_fk
FOREIGN KEY (m2m_num)
REFERENCES minidb.invm2m_hdr (m2m_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mditem_price ADD CONSTRAINT mdprice_cat_mditem_price_fk
FOREIGN KEY (cdpcat)
REFERENCES minidb.mdprice_cat (cdpcat)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdprice_formula ADD CONSTRAINT mdprice_cat_mdprice_formula_fk
FOREIGN KEY (cdpcat)
REFERENCES minidb.mdprice_cat (cdpcat)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdvendor ADD CONSTRAINT mdvendor_cat_mdvendor_fk
FOREIGN KEY (cdvendcat)
REFERENCES minidb.mdvendor_cat (cdvendcat)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mditem_vendor ADD CONSTRAINT mdvendor_mditem_vendor_fk
FOREIGN KEY (cdvend)
REFERENCES minidb.mdvendor (cdvend)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_hdr ADD CONSTRAINT mdvendor_invpurch_hdr_fk
FOREIGN KEY (cdvend)
REFERENCES minidb.mdvendor (cdvend)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_hdr ADD CONSTRAINT mdvendor_sales_hdr_fk
FOREIGN KEY (cdvend)
REFERENCES minidb.mdvendor (cdvend)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_hutang ADD CONSTRAINT mdvendor_fico_hutang_fk
FOREIGN KEY (cdvend)
REFERENCES minidb.mdvendor (cdvend)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_dtl ADD CONSTRAINT sales_hdr_sales_dtl_fk
FOREIGN KEY (sal_num)
REFERENCES minidb.sales_hdr (sal_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_dtl ADD CONSTRAINT invgr_hdr_invgr_dtl_fk
FOREIGN KEY (purch_num)
REFERENCES minidb.invpurch_hdr (purch_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_hutang ADD CONSTRAINT invpurch_hdr_fico_hutang_fk
FOREIGN KEY (purch_num)
REFERENCES minidb.invpurch_hdr (purch_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.fico_bayar ADD CONSTRAINT fico_hutang_fico_bayar_fk
FOREIGN KEY (cdvend, purch_num)
REFERENCES minidb.fico_hutang (cdvend, purch_num)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdItem ADD CONSTRAINT mditem_category_mditem_fk
FOREIGN KEY (cdicat)
REFERENCES minidb.mditem_category (cdicat)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdprice_formula ADD CONSTRAINT mditem_category_mdprice_formula_fk
FOREIGN KEY (cdicat)
REFERENCES minidb.mditem_category (cdicat)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdItem ADD CONSTRAINT mditem_group_mditem_fk
FOREIGN KEY (cdgroup)
REFERENCES minidb.mditem_group (cdgroup)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mdItem ADD CONSTRAINT mditem_uom_mditem_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mduom_conf ADD CONSTRAINT mditem_uom_mduom_conf_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mduom_conf ADD CONSTRAINT mditem_uom_mduom_conf_fk1
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mditem_price ADD CONSTRAINT mditem_uom_mditem_price_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_dtl ADD CONSTRAINT mditem_uom_invgr_dtl_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invmv_stock ADD CONSTRAINT mditem_uom_invmv_stock_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_dtl ADD CONSTRAINT mditem_uom_invgr_dtl_fk1
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_dtl ADD CONSTRAINT mditem_uom_invgi_dtl_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_dtl ADD CONSTRAINT mditem_uom_invtrf_dtl_fk
FOREIGN KEY (cduom)
REFERENCES minidb.mditem_uom (cduom)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mditem_vendor ADD CONSTRAINT mditem_mditem_vendor_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mditem_price ADD CONSTRAINT mditem_mditem_price_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invpurch_dtl ADD CONSTRAINT mditem_invgr_dtl_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgi_dtl ADD CONSTRAINT mditem_invgi_dtl_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invtrf_dtl ADD CONSTRAINT mditem_invmv_dtl_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.mduom_conf ADD CONSTRAINT mditem_mduom_conf_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invmv_stock ADD CONSTRAINT mditem_invmv_stock_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invgr_dtl ADD CONSTRAINT mditem_invgr_dtl_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.sales_dtl ADD CONSTRAINT mditem_sales_dtl_fk
FOREIGN KEY (cditem, lnitem)
REFERENCES minidb.mdItem (cditem, lnitem)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE minidb.invm2m_dtl ADD CONSTRAINT mduom_conf_invm2m_dtl_fk
FOREIGN KEY (cditem, lnitem, cduom, cduom2)
REFERENCES minidb.mduom_conf (cditem, lnitem, cduom, cduom2)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
