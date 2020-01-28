<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: issqn
//CLASSE DA ENTIDADE issarqsimplesreg
class cl_issarqsimplesreg { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $q23_sequencial = 0; 
   var $q23_issarqsimples = 0; 
   var $q23_seqreg = 0; 
   var $q23_dtarrec_dia = null; 
   var $q23_dtarrec_mes = null; 
   var $q23_dtarrec_ano = null; 
   var $q23_dtarrec = null; 
   var $q23_dtvenc_dia = null; 
   var $q23_dtvenc_mes = null; 
   var $q23_dtvenc_ano = null; 
   var $q23_dtvenc = null; 
   var $q23_cnpj = null; 
   var $q23_tiporec = null; 
   var $q23_vlrprinc = 0; 
   var $q23_vlrmul = 0; 
   var $q23_vlrjur = 0; 
   var $q23_data_dia = null; 
   var $q23_data_mes = null; 
   var $q23_data_ano = null; 
   var $q23_data = null; 
   var $q23_vlraut = 0; 
   var $q23_nroaut = null; 
   var $q23_codbco = null; 
   var $q23_codage = null; 
   var $q23_codsiafi = 0; 
   var $q23_codserpro = 0; 
   var $q23_anousu = 0; 
   var $q23_mesusu = 0; 
   var $q23_acao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q23_sequencial = int4 = Sequencial 
                 q23_issarqsimples = int4 = Sequencial 
                 q23_seqreg = int4 = Sequencial do Registro 
                 q23_dtarrec = date = Data Arrecadação 
                 q23_dtvenc = date = Data Vencimento 
                 q23_cnpj = varchar(14) = CNPJ 
                 q23_tiporec = char(1) = Tipo 
                 q23_vlrprinc = float8 = Valor Principal 
                 q23_vlrmul = float8 = Valor Multa 
                 q23_vlrjur = float8 = Valor Juro 
                 q23_data = date = Data 
                 q23_vlraut = float8 = Valor Autenticação 
                 q23_nroaut = varchar(23) = Numero Autenticação 
                 q23_codbco = char(3) = Codigo Banco 
                 q23_codage = char(4) = Codigo Agencia 
                 q23_codsiafi = int4 = Codigo Siafi 
                 q23_codserpro = int8 = Codigo Serpro 
                 q23_anousu = int4 = Ano 
                 q23_mesusu = int4 = Mês 
                 q23_acao = int4 = Ação 
                 ";
   //funcao construtor da classe 
   function cl_issarqsimplesreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarqsimplesreg"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->q23_sequencial = ($this->q23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_sequencial"]:$this->q23_sequencial);
       $this->q23_issarqsimples = ($this->q23_issarqsimples == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_issarqsimples"]:$this->q23_issarqsimples);
       $this->q23_seqreg = ($this->q23_seqreg == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_seqreg"]:$this->q23_seqreg);
       if($this->q23_dtarrec == ""){
         $this->q23_dtarrec_dia = ($this->q23_dtarrec_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_dia"]:$this->q23_dtarrec_dia);
         $this->q23_dtarrec_mes = ($this->q23_dtarrec_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_mes"]:$this->q23_dtarrec_mes);
         $this->q23_dtarrec_ano = ($this->q23_dtarrec_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_ano"]:$this->q23_dtarrec_ano);
         if($this->q23_dtarrec_dia != ""){
            $this->q23_dtarrec = $this->q23_dtarrec_ano."-".$this->q23_dtarrec_mes."-".$this->q23_dtarrec_dia;
         }
       }
       if($this->q23_dtvenc == ""){
         $this->q23_dtvenc_dia = ($this->q23_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_dia"]:$this->q23_dtvenc_dia);
         $this->q23_dtvenc_mes = ($this->q23_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_mes"]:$this->q23_dtvenc_mes);
         $this->q23_dtvenc_ano = ($this->q23_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_ano"]:$this->q23_dtvenc_ano);
         if($this->q23_dtvenc_dia != ""){
            $this->q23_dtvenc = $this->q23_dtvenc_ano."-".$this->q23_dtvenc_mes."-".$this->q23_dtvenc_dia;
         }
       }
       $this->q23_cnpj = ($this->q23_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_cnpj"]:$this->q23_cnpj);
       $this->q23_tiporec = ($this->q23_tiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_tiporec"]:$this->q23_tiporec);
       $this->q23_vlrprinc = ($this->q23_vlrprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_vlrprinc"]:$this->q23_vlrprinc);
       $this->q23_vlrmul = ($this->q23_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_vlrmul"]:$this->q23_vlrmul);
       $this->q23_vlrjur = ($this->q23_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_vlrjur"]:$this->q23_vlrjur);
       if($this->q23_data == ""){
         $this->q23_data_dia = ($this->q23_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_data_dia"]:$this->q23_data_dia);
         $this->q23_data_mes = ($this->q23_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_data_mes"]:$this->q23_data_mes);
         $this->q23_data_ano = ($this->q23_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_data_ano"]:$this->q23_data_ano);
         if($this->q23_data_dia != ""){
            $this->q23_data = $this->q23_data_ano."-".$this->q23_data_mes."-".$this->q23_data_dia;
         }
       }
       $this->q23_vlraut = ($this->q23_vlraut == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_vlraut"]:$this->q23_vlraut);
       $this->q23_nroaut = ($this->q23_nroaut == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_nroaut"]:$this->q23_nroaut);
       $this->q23_codbco = ($this->q23_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_codbco"]:$this->q23_codbco);
       $this->q23_codage = ($this->q23_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_codage"]:$this->q23_codage);
       $this->q23_codsiafi = ($this->q23_codsiafi == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_codsiafi"]:$this->q23_codsiafi);
       $this->q23_codserpro = ($this->q23_codserpro == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_codserpro"]:$this->q23_codserpro);
       $this->q23_anousu = ($this->q23_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_anousu"]:$this->q23_anousu);
       $this->q23_mesusu = ($this->q23_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_mesusu"]:$this->q23_mesusu);
       $this->q23_acao = ($this->q23_acao == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_acao"]:$this->q23_acao);
     }else{
       $this->q23_sequencial = ($this->q23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q23_sequencial"]:$this->q23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q23_sequencial){ 
      $this->atualizacampos();
     if($this->q23_issarqsimples == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "q23_issarqsimples";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q23_seqreg == null ){ 
       $this->q23_seqreg = "0";
     }
     if($this->q23_dtarrec == null ){ 
       $this->q23_dtarrec = "null";
     }
     if($this->q23_dtvenc == null ){ 
       $this->q23_dtvenc = "null";
     }
     if($this->q23_vlrprinc == null ){ 
       $this->q23_vlrprinc = "0";
     }
     if($this->q23_vlrmul == null ){ 
       $this->q23_vlrmul = "0";
     }
     if($this->q23_vlrjur == null ){ 
       $this->q23_vlrjur = "0";
     }
     if($this->q23_data == null ){ 
       $this->q23_data = "null";
     }
     if($this->q23_vlraut == null ){ 
       $this->q23_vlraut = "0";
     }
     if($this->q23_codsiafi == null ){ 
       $this->q23_codsiafi = "0";
     }
     if($this->q23_codserpro == null ){ 
       $this->q23_codserpro = "0";
     }
     if($this->q23_anousu == null ){ 
       $this->q23_anousu = "0";
     }
     if($this->q23_mesusu == null ){ 
       $this->q23_mesusu = "0";
     }
     if($this->q23_acao == null ){ 
       $this->erro_sql = " Campo Ação nao Informado.";
       $this->erro_campo = "q23_acao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q23_sequencial == "" || $q23_sequencial == null ){
       $result = db_query("select nextval('issarqsimplesreg_q23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarqsimplesreg_q23_sequencial_seq do campo: q23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issarqsimplesreg_q23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q23_sequencial)){
         $this->erro_sql = " Campo q23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q23_sequencial = $q23_sequencial; 
       }
     }
     if(($this->q23_sequencial == null) || ($this->q23_sequencial == "") ){ 
       $this->erro_sql = " Campo q23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarqsimplesreg(
                                       q23_sequencial 
                                      ,q23_issarqsimples 
                                      ,q23_seqreg 
                                      ,q23_dtarrec 
                                      ,q23_dtvenc 
                                      ,q23_cnpj 
                                      ,q23_tiporec 
                                      ,q23_vlrprinc 
                                      ,q23_vlrmul 
                                      ,q23_vlrjur 
                                      ,q23_data 
                                      ,q23_vlraut 
                                      ,q23_nroaut 
                                      ,q23_codbco 
                                      ,q23_codage 
                                      ,q23_codsiafi 
                                      ,q23_codserpro 
                                      ,q23_anousu 
                                      ,q23_mesusu 
                                      ,q23_acao 
                       )
                values (
                                $this->q23_sequencial 
                               ,$this->q23_issarqsimples 
                               ,$this->q23_seqreg 
                               ,".($this->q23_dtarrec == "null" || $this->q23_dtarrec == ""?"null":"'".$this->q23_dtarrec."'")." 
                               ,".($this->q23_dtvenc == "null" || $this->q23_dtvenc == ""?"null":"'".$this->q23_dtvenc."'")." 
                               ,'$this->q23_cnpj' 
                               ,'$this->q23_tiporec' 
                               ,$this->q23_vlrprinc 
                               ,$this->q23_vlrmul 
                               ,$this->q23_vlrjur 
                               ,".($this->q23_data == "null" || $this->q23_data == ""?"null":"'".$this->q23_data."'")." 
                               ,$this->q23_vlraut 
                               ,'$this->q23_nroaut' 
                               ,'$this->q23_codbco' 
                               ,'$this->q23_codage' 
                               ,$this->q23_codsiafi 
                               ,$this->q23_codserpro 
                               ,$this->q23_anousu 
                               ,$this->q23_mesusu 
                               ,$this->q23_acao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "issarqsimplesreg ($this->q23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "issarqsimplesreg já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "issarqsimplesreg ($this->q23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10693,'$this->q23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1845,10693,'','".AddSlashes(pg_result($resaco,0,'q23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10694,'','".AddSlashes(pg_result($resaco,0,'q23_issarqsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10695,'','".AddSlashes(pg_result($resaco,0,'q23_seqreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10696,'','".AddSlashes(pg_result($resaco,0,'q23_dtarrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10697,'','".AddSlashes(pg_result($resaco,0,'q23_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10698,'','".AddSlashes(pg_result($resaco,0,'q23_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10699,'','".AddSlashes(pg_result($resaco,0,'q23_tiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10701,'','".AddSlashes(pg_result($resaco,0,'q23_vlrprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10702,'','".AddSlashes(pg_result($resaco,0,'q23_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10703,'','".AddSlashes(pg_result($resaco,0,'q23_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10704,'','".AddSlashes(pg_result($resaco,0,'q23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10705,'','".AddSlashes(pg_result($resaco,0,'q23_vlraut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10706,'','".AddSlashes(pg_result($resaco,0,'q23_nroaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10707,'','".AddSlashes(pg_result($resaco,0,'q23_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10708,'','".AddSlashes(pg_result($resaco,0,'q23_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10709,'','".AddSlashes(pg_result($resaco,0,'q23_codsiafi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10710,'','".AddSlashes(pg_result($resaco,0,'q23_codserpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10721,'','".AddSlashes(pg_result($resaco,0,'q23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10722,'','".AddSlashes(pg_result($resaco,0,'q23_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1845,10839,'','".AddSlashes(pg_result($resaco,0,'q23_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issarqsimplesreg set ";
     $virgula = "";
     if(trim($this->q23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_sequencial"])){ 
       $sql  .= $virgula." q23_sequencial = $this->q23_sequencial ";
       $virgula = ",";
       if(trim($this->q23_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q23_issarqsimples)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_issarqsimples"])){ 
       $sql  .= $virgula." q23_issarqsimples = $this->q23_issarqsimples ";
       $virgula = ",";
       if(trim($this->q23_issarqsimples) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q23_issarqsimples";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q23_seqreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_seqreg"])){ 
        if(trim($this->q23_seqreg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_seqreg"])){ 
           $this->q23_seqreg = "0" ; 
        } 
       $sql  .= $virgula." q23_seqreg = $this->q23_seqreg ";
       $virgula = ",";
     }
     if(trim($this->q23_dtarrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_dia"] !="") ){ 
       $sql  .= $virgula." q23_dtarrec = '$this->q23_dtarrec' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q23_dtarrec_dia"])){ 
         $sql  .= $virgula." q23_dtarrec = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q23_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." q23_dtvenc = '$this->q23_dtvenc' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q23_dtvenc_dia"])){ 
         $sql  .= $virgula." q23_dtvenc = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q23_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_cnpj"])){ 
       $sql  .= $virgula." q23_cnpj = '$this->q23_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->q23_tiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_tiporec"])){ 
       $sql  .= $virgula." q23_tiporec = '$this->q23_tiporec' ";
       $virgula = ",";
     }
     if(trim($this->q23_vlrprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrprinc"])){ 
        if(trim($this->q23_vlrprinc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrprinc"])){ 
           $this->q23_vlrprinc = "0" ; 
        } 
       $sql  .= $virgula." q23_vlrprinc = $this->q23_vlrprinc ";
       $virgula = ",";
     }
     if(trim($this->q23_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrmul"])){ 
        if(trim($this->q23_vlrmul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrmul"])){ 
           $this->q23_vlrmul = "0" ; 
        } 
       $sql  .= $virgula." q23_vlrmul = $this->q23_vlrmul ";
       $virgula = ",";
     }
     if(trim($this->q23_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrjur"])){ 
        if(trim($this->q23_vlrjur)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrjur"])){ 
           $this->q23_vlrjur = "0" ; 
        } 
       $sql  .= $virgula." q23_vlrjur = $this->q23_vlrjur ";
       $virgula = ",";
     }
     if(trim($this->q23_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q23_data_dia"] !="") ){ 
       $sql  .= $virgula." q23_data = '$this->q23_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q23_data_dia"])){ 
         $sql  .= $virgula." q23_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q23_vlraut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_vlraut"])){ 
        if(trim($this->q23_vlraut)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_vlraut"])){ 
           $this->q23_vlraut = "0" ; 
        } 
       $sql  .= $virgula." q23_vlraut = $this->q23_vlraut ";
       $virgula = ",";
     }
     if(trim($this->q23_nroaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_nroaut"])){ 
       $sql  .= $virgula." q23_nroaut = '$this->q23_nroaut' ";
       $virgula = ",";
     }
     if(trim($this->q23_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_codbco"])){ 
       $sql  .= $virgula." q23_codbco = '$this->q23_codbco' ";
       $virgula = ",";
     }
     if(trim($this->q23_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_codage"])){ 
       $sql  .= $virgula." q23_codage = '$this->q23_codage' ";
       $virgula = ",";
     }
     if(trim($this->q23_codsiafi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_codsiafi"])){ 
        if(trim($this->q23_codsiafi)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_codsiafi"])){ 
           $this->q23_codsiafi = "0" ; 
        } 
       $sql  .= $virgula." q23_codsiafi = $this->q23_codsiafi ";
       $virgula = ",";
     }
     if(trim($this->q23_codserpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_codserpro"])){ 
        if(trim($this->q23_codserpro)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_codserpro"])){ 
           $this->q23_codserpro = "0" ; 
        } 
       $sql  .= $virgula." q23_codserpro = $this->q23_codserpro ";
       $virgula = ",";
     }
     if(trim($this->q23_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_anousu"])){ 
        if(trim($this->q23_anousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_anousu"])){ 
           $this->q23_anousu = "0" ; 
        } 
       $sql  .= $virgula." q23_anousu = $this->q23_anousu ";
       $virgula = ",";
     }
     if(trim($this->q23_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_mesusu"])){ 
        if(trim($this->q23_mesusu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q23_mesusu"])){ 
           $this->q23_mesusu = "0" ; 
        } 
       $sql  .= $virgula." q23_mesusu = $this->q23_mesusu ";
       $virgula = ",";
     }
     if(trim($this->q23_acao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q23_acao"])){ 
       $sql  .= $virgula." q23_acao = $this->q23_acao ";
       $virgula = ",";
       if(trim($this->q23_acao) == null ){ 
         $this->erro_sql = " Campo Ação nao Informado.";
         $this->erro_campo = "q23_acao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q23_sequencial!=null){
       $sql .= " q23_sequencial = $this->q23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10693,'$this->q23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1845,10693,'".AddSlashes(pg_result($resaco,$conresaco,'q23_sequencial'))."','$this->q23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_issarqsimples"]))
           $resac = db_query("insert into db_acount values($acount,1845,10694,'".AddSlashes(pg_result($resaco,$conresaco,'q23_issarqsimples'))."','$this->q23_issarqsimples',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_seqreg"]))
           $resac = db_query("insert into db_acount values($acount,1845,10695,'".AddSlashes(pg_result($resaco,$conresaco,'q23_seqreg'))."','$this->q23_seqreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_dtarrec"]))
           $resac = db_query("insert into db_acount values($acount,1845,10696,'".AddSlashes(pg_result($resaco,$conresaco,'q23_dtarrec'))."','$this->q23_dtarrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,1845,10697,'".AddSlashes(pg_result($resaco,$conresaco,'q23_dtvenc'))."','$this->q23_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_cnpj"]))
           $resac = db_query("insert into db_acount values($acount,1845,10698,'".AddSlashes(pg_result($resaco,$conresaco,'q23_cnpj'))."','$this->q23_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_tiporec"]))
           $resac = db_query("insert into db_acount values($acount,1845,10699,'".AddSlashes(pg_result($resaco,$conresaco,'q23_tiporec'))."','$this->q23_tiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrprinc"]))
           $resac = db_query("insert into db_acount values($acount,1845,10701,'".AddSlashes(pg_result($resaco,$conresaco,'q23_vlrprinc'))."','$this->q23_vlrprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,1845,10702,'".AddSlashes(pg_result($resaco,$conresaco,'q23_vlrmul'))."','$this->q23_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,1845,10703,'".AddSlashes(pg_result($resaco,$conresaco,'q23_vlrjur'))."','$this->q23_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_data"]))
           $resac = db_query("insert into db_acount values($acount,1845,10704,'".AddSlashes(pg_result($resaco,$conresaco,'q23_data'))."','$this->q23_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_vlraut"]))
           $resac = db_query("insert into db_acount values($acount,1845,10705,'".AddSlashes(pg_result($resaco,$conresaco,'q23_vlraut'))."','$this->q23_vlraut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_nroaut"]))
           $resac = db_query("insert into db_acount values($acount,1845,10706,'".AddSlashes(pg_result($resaco,$conresaco,'q23_nroaut'))."','$this->q23_nroaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_codbco"]))
           $resac = db_query("insert into db_acount values($acount,1845,10707,'".AddSlashes(pg_result($resaco,$conresaco,'q23_codbco'))."','$this->q23_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_codage"]))
           $resac = db_query("insert into db_acount values($acount,1845,10708,'".AddSlashes(pg_result($resaco,$conresaco,'q23_codage'))."','$this->q23_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_codsiafi"]))
           $resac = db_query("insert into db_acount values($acount,1845,10709,'".AddSlashes(pg_result($resaco,$conresaco,'q23_codsiafi'))."','$this->q23_codsiafi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_codserpro"]))
           $resac = db_query("insert into db_acount values($acount,1845,10710,'".AddSlashes(pg_result($resaco,$conresaco,'q23_codserpro'))."','$this->q23_codserpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1845,10721,'".AddSlashes(pg_result($resaco,$conresaco,'q23_anousu'))."','$this->q23_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,1845,10722,'".AddSlashes(pg_result($resaco,$conresaco,'q23_mesusu'))."','$this->q23_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q23_acao"]))
           $resac = db_query("insert into db_acount values($acount,1845,10839,'".AddSlashes(pg_result($resaco,$conresaco,'q23_acao'))."','$this->q23_acao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issarqsimplesreg nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issarqsimplesreg nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10693,'$q23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1845,10693,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10694,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_issarqsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10695,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_seqreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10696,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_dtarrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10697,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10698,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10699,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_tiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10701,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_vlrprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10702,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10703,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10704,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10705,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_vlraut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10706,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_nroaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10707,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10708,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10709,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_codsiafi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10710,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_codserpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10721,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10722,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1845,10839,'','".AddSlashes(pg_result($resaco,$iresaco,'q23_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issarqsimplesreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q23_sequencial = $q23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issarqsimplesreg nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issarqsimplesreg nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:issarqsimplesreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from issarqsimplesreg ";
     $sql .= "      inner join issarqsimples  on  issarqsimples.q17_sequencial = issarqsimplesreg.q23_issarqsimples";
     $sql .= "      inner join db_config  on  db_config.codigo = issarqsimples.q17_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($q23_sequencial!=null ){
         $sql2 .= " where issarqsimplesreg.q23_sequencial = $q23_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }   
     }
     return $sql;
  }
   function sql_query_file ( $q23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from issarqsimplesreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($q23_sequencial!=null ){
         $sql2 .= " where issarqsimplesreg.q23_sequencial = $q23_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  /**
   * Sql para buscar registros para processamento do arquivo do simples
   * @param string  $sCampos
   * @param string  $sWhere
   * @param string  $sOrderBy
   * @return string
   */
  function sql_query_registrosProcessamentoArquivo($sCampos = "*", $sWhere = null, $sGroupBy = null, $sOrderBy = null) {

  	
  	
  	$sql  = "select case when quantidade_cgm = 1                                                                                ";
    $sql .= "            then true                                                                                              ";
    $sql .= "            else false                                                                                             ";
    $sql .= "        end as cnpj_valido,                                                                                        ";
    $sql .= "       *                                                                                                           ";
    $sql .= "       from (select (select count(z01_numcgm)                                                                      ";
    $sql .= "                       from cgm                                                                                    ";
    $sql .= "                      where z01_cgccpf =issarqsimplesreg.q23_cnpj) as quantidade_cgm,                              ";
  	$sql .=                      !empty($sCampos) ? $sCampos : "*";
    $sql .= "               from issarqsimplesreg                                                                               ";
    $sql .= "                    inner join issarqsimples                                                                       ";
    $sql .= "                            on issarqsimples.q17_sequencial                  = issarqsimplesreg.q23_issarqsimples  ";
    $sql .= "                    left  join issarqsimplesregissbase                                                             ";
    $sql .= "                            on issarqsimplesregissbase.q134_issarqsimplesreg = issarqsimplesreg.q23_sequencial     ";
    $sql .= "                    inner join db_config                                                                           ";
    $sql .= "                            on db_config.codigo                              = issarqsimples.q17_instit            ";
  	$sql .= !empty($sWhere)   ? " where    {$sWhere}        \n" : ""; 
  	$sql .= !empty($sGroupBy) ? " group by {$sGroupBy}      \n" : ""; 
  	$sql .= !empty($sOrderBy) ? " order by {$sOrderBy}      \n" : "";
    $sql .= "            ) as x                                                                                                 ";
  	return $sql;
  }
}
?>