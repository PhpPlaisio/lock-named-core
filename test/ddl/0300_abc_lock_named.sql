insert into ABC_LOCK_NAMED( cmp_id
,                           lnm_name
,                           lnm_label)
values( 1
,       'named_lock1'
,       'LNM_ID_SYS_NAMED_LOCK1')
,     ( 1
,       'named_lock2'
,       'LNM_ID_SYS_NAMED_LOCK2')
,     ( 2
,       'named_lock1'
,       'LNM_ID_ABC_NAMED_LOCK1')
,     ( 2
,       'named_lock2'
,       'LNM_ID_ABC_NAMED_LOCK2')
;
;

commit;