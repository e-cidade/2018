<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: tributario
//CLASSE DA ENTIDADE isencao
class cl_isencao { 
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
   var $v10_sequencial = 0; 
   var $v10_isencaotipo = 0; 
   var $v10_dtisen_dia = null; 
   var $v10_dtisen_mes = null; 
   var $v10_dtisen_ano = null; 
   var $v10_dtisen = null; 
   var $v10_dtlan_dia = null; 
   var $v10_dtlan_mes = null; 
   var $v10_dtlan_ano = null; 
   var $v10_dtlan = null; 
   var $v10_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v10_sequencial = int4 = Codigo da isenção 
                 v10_isencaotipo = int4 = Codigo do tipo de isenção 
                 v10_dtisen = date = Data da isenção 
                 v10_dtlan = date = Data do lançamento 
                 v10_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_isencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("isencao"); 
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
       $this->v10_sequencial = ($this->v10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_sequencial"]:$this->v10_sequencial);
       $this->v10_isencaotipo = ($this->v10_isencaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_isencaotipo"]:$this->v10_isencaotipo);
       if($this->v10_dtisen == ""){
         $this->v10_dtisen_dia = ($this->v10_dtisen_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtisen_dia"]:$this->v10_dtisen_dia);
         $this->v10_dtisen_mes = ($this->v10_dtisen_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtisen_mes"]:$this->v10_dtisen_mes);
         $this->v10_dtisen_ano = ($this->v10_dtisen_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtisen_ano"]:$this->v10_dtisen_ano);
         if($this->v10_dtisen_dia != ""){
            $this->v10_dtisen = $this->v10_dtisen_ano."-".$this->v10_dtisen_mes."-".$this->v10_dtisen_dia;
         }
       }
       if($this->v10_dtlan == ""){
         $this->v10_dtlan_dia = ($this->v10_dtlan_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtlan_dia"]:$this->v10_dtlan_dia);
         $this->v10_dtlan_mes = ($this->v10_dtlan_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtlan_mes"]:$this->v10_dtlan_mes);
         $this->v10_dtlan_ano = ($this->v10_dtlan_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_dtlan_ano"]:$this->v10_dtlan_ano);
         if($this->v10_dtlan_dia != ""){
            $this->v10_dtlan = $this->v10_dtlan_ano."-".$this->v10_dtlan_mes."-".$this->v10_dtlan_dia;
         }
       }
       $this->v10_usuario = ($this->v10_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_usuario"]:$this->v10_usuario);
     }else{
       $this->v10_sequencial = ($this->v10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v10_sequencial"]:$this->v10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v10_sequencial){ 
      $this->atualizacampos();
     if($this->v10_isencaotipo == null ){ 
       $this->erro_sql = " Campo Codigo do tipo de isenção nao Informado.";
       $this->erro_campo = "v10_isencaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v10_dtisen == null ){ 
       $this->erro_sql = " Campo Data da isenção nao Informado.";
       $this->erro_campo = "v10_dtisen_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v10_dtlan == null ){ 
       $this->erro_sql = " Campo Data do lançamento nao Informado.";
       $this->erro_campo = "v10_dtlan_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v10_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "v10_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v10_sequencial == "" || $v10_sequencial == null ){
       $result = db_query("select nextval('isencao_v10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: isencao_v10_sequencial_seq do campo: v10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from isencao_v10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v10_sequencial)){
         $this->erro_sql = " Campo v10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v10_sequencial = $v10_sequencial; 
       }
     }
     if(($this->v10_sequencial == null) || ($this->v10_sequencial == "") ){ 
       $this->erro_sql = " Campo v10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isencao(
                                       v10_sequencial 
                                      ,v10_isencaotipo 
                                      ,v10_dtisen 
                                      ,v10_dtlan 
                                      ,v10_usuario 
                       )
                values (
                                $this->v10_sequencial 
                               ,$this->v10_isencaotipo 
                               ,".($this->v10_dtisen == "null" || $this->v10_dtisen == ""?"null":"'".$this->v10_dtisen."'")." 
                               ,".($this->v10_dtlan == "null" || $this->v10_dtlan == ""?"null":"'".$this->v10_dtlan."'")." 
                               ,$this->v10_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de isenções ($this->v10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de isenções já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de isenções ($this->v10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v10_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9927,'$this->v10_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1707,9927,'','".AddSlashes(pg_result($resaco,0,'v10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1707,9929,'','".AddSlashes(pg_result($resaco,0,'v10_isencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1707,9930,'','".AddSlashes(pg_result($resaco,0,'v10_dtisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1707,9931,'','".AddSlashes(pg_result($resaco,0,'v10_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1707,9932,'','".AddSlashes(pg_result($resaco,0,'v10_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update isencao set ";
     $virgula = "";
     if(trim($this->v10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v10_sequencial"])){ 
       $sql  .= $virgula." v10_sequencial = $this->v10_sequencial ";
       $virgula = ",";
       if(trim($this->v10_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo da isenção nao Informado.";
         $this->erro_campo = "v10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v10_isencaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v10_isencaotipo"])){ 
       $sql  .= $virgula." v10_isencaotipo = $this->v10_isencaotipo ";
       $virgula = ",";
       if(trim($this->v10_isencaotipo) == null ){ 
         $this->erro_sql = " Campo Codigo do tipo de isenção nao Informado.";
         $this->erro_campo = "v10_isencaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v10_dtisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v10_dtisen_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v10_dtisen_dia"] !="") ){ 
       $sql  .= $virgula." v10_dtisen = '$this->v10_dtisen' ";
       $virgula = ",";
       if(trim($this->v10_dtisen) == null ){ 
         $this->erro_sql = " Campo Data da isenção nao Informado.";
         $this->erro_campo = "v10_dtisen_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v10_dtisen_dia"])){ 
         $sql  .= $virgula." v10_dtisen = null ";
         $virgula = ",";
         if(trim($this->v10_dtisen) == null ){ 
           $this->erro_sql = " Campo Data da isenção nao Informado.";
           $this->erro_campo = "v10_dtisen_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v10_dtlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v10_dtlan_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v10_dtlan_dia"] !="") ){ 
       $sql  .= $virgula." v10_dtlan = '$this->v10_dtlan' ";
       $virgula = ",";
       if(trim($this->v10_dtlan) == null ){ 
         $this->erro_sql = " Campo Data do lançamento nao Informado.";
         $this->erro_campo = "v10_dtlan_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v10_dtlan_dia"])){ 
         $sql  .= $virgula." v10_dtlan = null ";
         $virgula = ",";
         if(trim($this->v10_dtlan) == null ){ 
           $this->erro_sql = " Campo Data do lançamento nao Informado.";
           $this->erro_campo = "v10_dtlan_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v10_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v10_usuario"])){ 
       $sql  .= $virgula." v10_usuario = $this->v10_usuario ";
       $virgula = ",";
       if(trim($this->v10_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "v10_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v10_sequencial!=null){
       $sql .= " v10_sequencial = $this->v10_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v10_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9927,'$this->v10_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v10_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1707,9927,'".AddSlashes(pg_result($resaco,$conresaco,'v10_sequencial'))."','$this->v10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v10_isencaotipo"]))
           $resac = db_query("insert into db_acount values($acount,1707,9929,'".AddSlashes(pg_result($resaco,$conresaco,'v10_isencaotipo'))."','$this->v10_isencaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v10_dtisen"]))
           $resac = db_query("insert into db_acount values($acount,1707,9930,'".AddSlashes(pg_result($resaco,$conresaco,'v10_dtisen'))."','$this->v10_dtisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v10_dtlan"]))
           $resac = db_query("insert into db_acount values($acount,1707,9931,'".AddSlashes(pg_result($resaco,$conresaco,'v10_dtlan'))."','$this->v10_dtlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v10_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1707,9932,'".AddSlashes(pg_result($resaco,$conresaco,'v10_usuario'))."','$this->v10_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de isenções nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de isenções nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v10_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v10_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9927,'$v10_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1707,9927,'','".AddSlashes(pg_result($resaco,$iresaco,'v10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1707,9929,'','".AddSlashes(pg_result($resaco,$iresaco,'v10_isencaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1707,9930,'','".AddSlashes(pg_result($resaco,$iresaco,'v10_dtisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1707,9931,'','".AddSlashes(pg_result($resaco,$iresaco,'v10_dtlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1707,9932,'','".AddSlashes(pg_result($resaco,$iresaco,'v10_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from isencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v10_sequencial = $v10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de isenções nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de isenções nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:isencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isencao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = isencao.v10_usuario";
     $sql .= "      inner join isencaotipo  on  isencaotipo.v11_sequencial = isencao.v10_isencaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($v10_sequencial!=null ){
         $sql2 .= " where isencao.v10_sequencial = $v10_sequencial "; 
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
   function sql_query_file ( $v10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($v10_sequencial!=null ){
         $sql2 .= " where isencao.v10_sequencial = $v10_sequencial "; 
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
   function sql_query_func ( $v10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from isencao ";
     $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario = isencao.v10_usuario";
     $sql .= "      inner join isencaotipo   on  isencaotipo.v11_sequencial = isencao.v10_isencaotipo";
     $sql .= "      left  join isencaocgm    on  isencaocgm.v12_isencao     = isencao.v10_sequencial";
     $sql .= "      left  join isencaomatric on  isencaomatric.v15_isencao  = isencao.v10_sequencial";
     $sql .= "      left  join isencaoinscr  on  isencaoinscr.v16_isencao   = isencao.v10_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($v10_sequencial!=null ){
         $sql2 .= " where isencao.v10_sequencial = $v10_sequencial "; 
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