/*================================================================================*/
/* DDL SCRIPT                                                                     */
/*================================================================================*/
/*  Title    :                                                                    */
/*  FileName : abc-lock-named-core.ecm                                            */
/*  Platform : MySQL 5.6                                                          */
/*  Version  : Concept                                                            */
/*  Date     : maandag 23 oktober 2017                                            */
/*================================================================================*/
/*================================================================================*/
/* CREATE TABLES                                                                  */
/*================================================================================*/

CREATE TABLE ABC_LOCK_NAMED_NAME (
  lnn_id SMALLINT AUTO_INCREMENT NOT NULL,
  lnn_name VARCHAR(200) NOT NULL,
  lnn_label VARCHAR(200) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  CONSTRAINT PK_ABC_LOCK_NAMED_NAME PRIMARY KEY (lnn_id)
);

CREATE TABLE ABC_LOCK_NAMED (
  lnm_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  cmp_id SMALLINT UNSIGNED NOT NULL,
  lnn_id SMALLINT NOT NULL,
  CONSTRAINT PK_ABC_LOCK_NAMED PRIMARY KEY (lnm_id)
);

/*================================================================================*/
/* CREATE INDEXES                                                                 */
/*================================================================================*/

CREATE UNIQUE INDEX IX_ABC_LOCK_NAMED_NAME1 ON ABC_LOCK_NAMED_NAME (lnn_name);

CREATE UNIQUE INDEX IX_ABC_LOCK_NAMED1 ON ABC_LOCK_NAMED (cmp_id, lnn_id);

/*================================================================================*/
/* CREATE FOREIGN KEYS                                                            */
/*================================================================================*/

ALTER TABLE ABC_LOCK_NAMED
  ADD CONSTRAINT FK_ABC_LOCK_NAMED_ABC_LOCK_NAMED_NAME
  FOREIGN KEY (lnn_id) REFERENCES ABC_LOCK_NAMED_NAME (lnn_id);

ALTER TABLE ABC_LOCK_NAMED
  ADD CONSTRAINT FK_ABC_LOCK_NAMED_AUT_COMPANY
  FOREIGN KEY (cmp_id) REFERENCES AUT_COMPANY (cmp_id);
