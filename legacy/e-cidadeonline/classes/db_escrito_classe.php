<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: ISSQN
//CLASSE DA ENTIDADE escrito
class cl_escrito { 
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
   var $q10_sequencial = 0; 
   var $q10_inscr = 0; 
   var $q10_numcgm = 0; 
   var $q10_dtini_dia = null; 
   var $q10_dtini_mes = null; 
   var $q10_dtini_ano = null; 
   var $q10_dtini = null; 
   var $q10_dtfim_dia = null; 
   var $q10_dtfim_mes = null; 
   var $q10_dtfim_ano = null; 
   var $q10_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q10_sequencial = int4 = Sequencial 
                 q10_inscr = int4 = inscricao 
                 q10_numcgm = int4 = CGM do escritório 
                 q10_dtini = date = q10_dtini 
                 q10_dtfim = date = q10_dtfim 
                 ";
   //funcao construtor da classe 
   function cl_escrito() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escrito"); 
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
       $this->q10_sequencial = ($this->q10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_sequencial"]:$this->q10_sequencial);
       $this->q10_inscr = ($this->q10_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_inscr"]:$this->q10_inscr);
       $this->q10_numcgm = ($this->q10_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_numcgm"]:$this->q10_numcgm);
       if($this->q10_dtini == ""){
         $this->q10_dtini_dia = ($this->q10_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"]:$this->q10_dtini_dia);
         $this->q10_dtini_mes = ($this->q10_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtini_mes"]:$this->q10_dtini_mes);
         $this->q10_dtini_ano = ($this->q10_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtini_ano"]:$this->q10_dtini_ano);
         if($this->q10_dtini_dia != ""){
            $this->q10_dtini = $this->q10_dtini_ano."-".$this->q10_dtini_mes."-".$this->q10_dtini_dia;
         }
       }
       if($this->q10_dtfim == ""){
         $this->q10_dtfim_dia = ($this->q10_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"]:$this->q10_dtfim_dia);
         $this->q10_dtfim_mes = ($this->q10_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtfim_mes"]:$this->q10_dtfim_mes);
         $this->q10_dtfim_ano = ($this->q10_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_dtfim_ano"]:$this->q10_dtfim_ano);
         if($this->q10_dtfim_dia != ""){
            $this->q10_dtfim = $this->q10_dtfim_ano."-".$this->q10_dtfim_mes."-".$this->q10_dtfim_dia;
         }
       }
     }else{
       $this->q10_sequencial = ($this->q10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q10_sequencial"]:$this->q10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q10_sequencial){ 
      $this->atualizacampos();
     if($this->q10_inscr == null ){ 
       $this->erro_sql = " Campo inscricao nao Informado.";
       $this->erro_campo = "q10_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q10_numcgm == null ){ 
       $this->q10_numcgm = "0";
     }
     if($this->q10_dtini == null ){ 
       $this->q10_dtini = "null";
     }
     if($this->q10_dtfim == null ){ 
       $this->q10_dtfim = "null";
     }
     if($q10_sequencial == "" || $q10_sequencial == null ){
       $result = db_query("select nextval('escrito_q10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro$clescrito->incluir da sequencia: escrito_q10_sequencial_seq do campo: q10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escrito_q10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q10_sequencial)){
         $this->erro_sql = " Campo q10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q10_sequencial = $q10_sequencial; 
       }
     }
     if(($this->q10_sequencial == null) || ($this->q10_sequencial == "") ){ 
       $this->erro_sql = " Campo q10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escrito(
                                       q10_sequencial 
                                      ,q10_inscr 
                                      ,q10_numcgm 
                                      ,q10_dtini 
                                      ,q10_dtfim 
                       )
                values (
                                $this->q10_sequencial 
                               ,$this->q10_inscr 
                               ,$this->q10_numcgm 
                               ,".($this->q10_dtini == "null" || $this->q10_dtini == ""?"null":"'".$this->q10_dtini."'")." 
                               ,".($this->q10_dtfim == "null" || $this->q10_dtfim == ""?"null":"'".$this->q10_dtfim."'")." 
                      )";                             
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14588,'$this->q10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,58,14588,'','".AddSlashes(pg_result($resaco,0,'q10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,58,308,'','".AddSlashes(pg_result($resaco,0,'q10_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,58,309,'','".AddSlashes(pg_result($resaco,0,'q10_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,58,14583,'','".AddSlashes(pg_result($resaco,0,'q10_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,58,14584,'','".AddSlashes(pg_result($resaco,0,'q10_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update escrito set ";
     $virgula = "";
     if(trim($this->q10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_sequencial"])){ 
       $sql  .= $virgula." q10_sequencial = $this->q10_sequencial ";
       $virgula = ",";
       if(trim($this->q10_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q10_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_inscr"])){ 
       $sql  .= $virgula." q10_inscr = $this->q10_inscr ";
       $virgula = ",";
       if(trim($this->q10_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q10_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q10_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"])){ 
        if(trim($this->q10_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"])){ 
           $this->q10_numcgm = "0" ; 
        } 
       $sql  .= $virgula." q10_numcgm = $this->q10_numcgm ";
       $virgula = ",";
     }
     if(trim($this->q10_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"] !="") ){ 
       $sql  .= $virgula." q10_dtini = '$this->q10_dtini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"])){ 
         $sql  .= $virgula." q10_dtini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q10_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." q10_dtfim = '$this->q10_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"])){ 
         $sql  .= $virgula." q10_dtfim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($q10_sequencial!=null){
       $sql .= " q10_sequencial = $this->q10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14588,'$this->q10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_sequencial"]) || $this->q10_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,58,14588,'".AddSlashes(pg_result($resaco,$conresaco,'q10_sequencial'))."','$this->q10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_inscr"]) || $this->q10_inscr != "")
           $resac = db_query("insert into db_acount values($acount,58,308,'".AddSlashes(pg_result($resaco,$conresaco,'q10_inscr'))."','$this->q10_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"]) || $this->q10_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,58,309,'".AddSlashes(pg_result($resaco,$conresaco,'q10_numcgm'))."','$this->q10_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini"]) || $this->q10_dtini != "")
           $resac = db_query("insert into db_acount values($acount,58,14583,'".AddSlashes(pg_result($resaco,$conresaco,'q10_dtini'))."','$this->q10_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim"]) || $this->q10_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,58,14584,'".AddSlashes(pg_result($resaco,$conresaco,'q10_dtfim'))."','$this->q10_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
   
   // alterar para campos data null
   function alterar_nulos($q10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update escrito set ";
     $virgula = "";
     if(trim($this->q10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_sequencial"])){ 
       $sql  .= $virgula." q10_sequencial = $this->q10_sequencial ";
       $virgula = ",";
       if(trim($this->q10_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q10_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_inscr"])){ 
       $sql  .= $virgula." q10_inscr = $this->q10_inscr ";
       $virgula = ",";
       if(trim($this->q10_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q10_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q10_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"])){ 
        if(trim($this->q10_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"])){ 
           $this->q10_numcgm = "0" ; 
        } 
       $sql  .= $virgula." q10_numcgm = $this->q10_numcgm ";
       $virgula = ",";
     }
     if( ( trim($this->q10_dtini)!="" && trim($this->q10_dtini) != 'null' ) || isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"] !="") ){ 
       $sql  .= $virgula." q10_dtini = '$this->q10_dtini' ";
       $virgula = ",";
     } else { 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini_dia"]) || trim($this->q10_dtini) == 'null'){ 
         $sql  .= $virgula." q10_dtini = null ";
         $virgula = ",";
       }
     }
     if( ( trim($this->q10_dtfim)!="" && trim($this->q10_dtfim) != 'null' ) || isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." q10_dtfim = '$this->q10_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim_dia"]) || trim($this->q10_dtfim) == 'null'){ 
         $sql  .= $virgula." q10_dtfim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($q10_sequencial!=null){
       $sql .= " q10_sequencial = $this->q10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14588,'$this->q10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_sequencial"]) || $this->q10_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,58,14588,'".AddSlashes(pg_result($resaco,$conresaco,'q10_sequencial'))."','$this->q10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_inscr"]) || $this->q10_inscr != "")
           $resac = db_query("insert into db_acount values($acount,58,308,'".AddSlashes(pg_result($resaco,$conresaco,'q10_inscr'))."','$this->q10_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_numcgm"]) || $this->q10_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,58,309,'".AddSlashes(pg_result($resaco,$conresaco,'q10_numcgm'))."','$this->q10_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtini"]) || $this->q10_dtini != "")
           $resac = db_query("insert into db_acount values($acount,58,14583,'".AddSlashes(pg_result($resaco,$conresaco,'q10_dtini'))."','$this->q10_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q10_dtfim"]) || $this->q10_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,58,14584,'".AddSlashes(pg_result($resaco,$conresaco,'q10_dtfim'))."','$this->q10_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }   
   
   
   // funcao para exclusao 
   function excluir ($q10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14588,'$q10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,58,14588,'','".AddSlashes(pg_result($resaco,$iresaco,'q10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,58,308,'','".AddSlashes(pg_result($resaco,$iresaco,'q10_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,58,309,'','".AddSlashes(pg_result($resaco,$iresaco,'q10_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,58,14583,'','".AddSlashes(pg_result($resaco,$iresaco,'q10_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,58,14584,'','".AddSlashes(pg_result($resaco,$iresaco,'q10_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escrito
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q10_sequencial = $q10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:escrito";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escrito ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = escrito.q10_inscr";
     $sql .= "      left  join cadescrito  on  cadescrito.q86_numcgm = escrito.q10_numcgm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = cadescrito.q86_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q10_sequencial!=null ){
         $sql2 .= " where escrito.q10_sequencial = $q10_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $q10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from escrito ";
     $sql2 = "";
     if($dbwhere==""){
       if($q10_sequencial!=null ){
         $sql2 .= " where escrito.q10_sequencial = $q10_sequencial "; 
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
}
?>