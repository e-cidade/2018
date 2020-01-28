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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitasituacao
class cl_liclicitasituacao { 
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
   var $l11_sequencial = 0; 
   var $l11_id_usuario = 0; 
   var $l11_licsituacao = 0; 
   var $l11_liclicita = 0; 
   var $l11_obs = null; 
   var $l11_data_dia = null; 
   var $l11_data_mes = null; 
   var $l11_data_ano = null; 
   var $l11_data = null; 
   var $l11_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l11_sequencial = int4 = Sequencial 
                 l11_id_usuario = int4 = Usuário 
                 l11_licsituacao = int4 = Situação: 
                 l11_liclicita = int4 = Licitação: 
                 l11_obs = text = Observação 
                 l11_data = date = Data do Movimento 
                 l11_hora = char(5) = Hora do Movimento 
                 ";
   //funcao construtor da classe 
   function cl_liclicitasituacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitasituacao"); 
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
       $this->l11_sequencial = ($this->l11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_sequencial"]:$this->l11_sequencial);
       $this->l11_id_usuario = ($this->l11_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_id_usuario"]:$this->l11_id_usuario);
       $this->l11_licsituacao = ($this->l11_licsituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_licsituacao"]:$this->l11_licsituacao);
       $this->l11_liclicita = ($this->l11_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_liclicita"]:$this->l11_liclicita);
       $this->l11_obs = ($this->l11_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_obs"]:$this->l11_obs);
       if($this->l11_data == ""){
         $this->l11_data_dia = ($this->l11_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_data_dia"]:$this->l11_data_dia);
         $this->l11_data_mes = ($this->l11_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_data_mes"]:$this->l11_data_mes);
         $this->l11_data_ano = ($this->l11_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_data_ano"]:$this->l11_data_ano);
         if($this->l11_data_dia != ""){
            $this->l11_data = $this->l11_data_ano."-".$this->l11_data_mes."-".$this->l11_data_dia;
         }
       }
       $this->l11_hora = ($this->l11_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_hora"]:$this->l11_hora);
     }else{
       $this->l11_sequencial = ($this->l11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l11_sequencial"]:$this->l11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l11_sequencial){ 
      $this->atualizacampos();
     if($this->l11_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "l11_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l11_licsituacao == null ){ 
       $this->erro_sql = " Campo Situação: nao Informado.";
       $this->erro_campo = "l11_licsituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l11_liclicita == null ){ 
       $this->erro_sql = " Campo Licitação: nao Informado.";
       $this->erro_campo = "l11_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l11_data == null ){ 
       $this->l11_data = "null";
     }
     if($this->l11_hora == null ){ 
       $this->erro_sql = " Campo Hora do Movimento nao Informado.";
       $this->erro_campo = "l11_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l11_sequencial == "" || $l11_sequencial == null ){
       $result = db_query("select nextval('liclicitasituacao_l11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitasituacao_l11_sequencial_seq do campo: l11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitasituacao_l11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l11_sequencial)){
         $this->erro_sql = " Campo l11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l11_sequencial = $l11_sequencial; 
       }
     }
     if(($this->l11_sequencial == null) || ($this->l11_sequencial == "") ){ 
       $this->erro_sql = " Campo l11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitasituacao(
                                       l11_sequencial 
                                      ,l11_id_usuario 
                                      ,l11_licsituacao 
                                      ,l11_liclicita 
                                      ,l11_obs 
                                      ,l11_data 
                                      ,l11_hora 
                       )
                values (
                                $this->l11_sequencial 
                               ,$this->l11_id_usuario 
                               ,$this->l11_licsituacao 
                               ,$this->l11_liclicita 
                               ,'$this->l11_obs' 
                               ,".($this->l11_data == "null" || $this->l11_data == ""?"null":"'".$this->l11_data."'")." 
                               ,'$this->l11_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Situações da Licitação ($this->l11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Situações da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Situações da Licitação ($this->l11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10279,'$this->l11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1777,10279,'','".AddSlashes(pg_result($resaco,0,'l11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10280,'','".AddSlashes(pg_result($resaco,0,'l11_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10281,'','".AddSlashes(pg_result($resaco,0,'l11_licsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10282,'','".AddSlashes(pg_result($resaco,0,'l11_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10283,'','".AddSlashes(pg_result($resaco,0,'l11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10284,'','".AddSlashes(pg_result($resaco,0,'l11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1777,10285,'','".AddSlashes(pg_result($resaco,0,'l11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitasituacao set ";
     $virgula = "";
     if(trim($this->l11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_sequencial"])){ 
       $sql  .= $virgula." l11_sequencial = $this->l11_sequencial ";
       $virgula = ",";
       if(trim($this->l11_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "l11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l11_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_id_usuario"])){ 
       $sql  .= $virgula." l11_id_usuario = $this->l11_id_usuario ";
       $virgula = ",";
       if(trim($this->l11_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "l11_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l11_licsituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_licsituacao"])){ 
       $sql  .= $virgula." l11_licsituacao = $this->l11_licsituacao ";
       $virgula = ",";
       if(trim($this->l11_licsituacao) == null ){ 
         $this->erro_sql = " Campo Situação: nao Informado.";
         $this->erro_campo = "l11_licsituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l11_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_liclicita"])){ 
       $sql  .= $virgula." l11_liclicita = $this->l11_liclicita ";
       $virgula = ",";
       if(trim($this->l11_liclicita) == null ){ 
         $this->erro_sql = " Campo Licitação: nao Informado.";
         $this->erro_campo = "l11_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l11_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_obs"])){ 
       $sql  .= $virgula." l11_obs = '$this->l11_obs' ";
       $virgula = ",";
     }
     if(trim($this->l11_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l11_data_dia"] !="") ){ 
       $sql  .= $virgula." l11_data = '$this->l11_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l11_data_dia"])){ 
         $sql  .= $virgula." l11_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->l11_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l11_hora"])){ 
       $sql  .= $virgula." l11_hora = '$this->l11_hora' ";
       $virgula = ",";
       if(trim($this->l11_hora) == null ){ 
         $this->erro_sql = " Campo Hora do Movimento nao Informado.";
         $this->erro_campo = "l11_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l11_sequencial!=null){
       $sql .= " l11_sequencial = $this->l11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10279,'$this->l11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1777,10279,'".AddSlashes(pg_result($resaco,$conresaco,'l11_sequencial'))."','$this->l11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1777,10280,'".AddSlashes(pg_result($resaco,$conresaco,'l11_id_usuario'))."','$this->l11_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_licsituacao"]))
           $resac = db_query("insert into db_acount values($acount,1777,10281,'".AddSlashes(pg_result($resaco,$conresaco,'l11_licsituacao'))."','$this->l11_licsituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_liclicita"]))
           $resac = db_query("insert into db_acount values($acount,1777,10282,'".AddSlashes(pg_result($resaco,$conresaco,'l11_liclicita'))."','$this->l11_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_obs"]))
           $resac = db_query("insert into db_acount values($acount,1777,10283,'".AddSlashes(pg_result($resaco,$conresaco,'l11_obs'))."','$this->l11_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_data"]))
           $resac = db_query("insert into db_acount values($acount,1777,10284,'".AddSlashes(pg_result($resaco,$conresaco,'l11_data'))."','$this->l11_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l11_hora"]))
           $resac = db_query("insert into db_acount values($acount,1777,10285,'".AddSlashes(pg_result($resaco,$conresaco,'l11_hora'))."','$this->l11_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situações da Licitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situações da Licitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10279,'$l11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1777,10279,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10280,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10281,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_licsituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10282,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10283,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10284,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1777,10285,'','".AddSlashes(pg_result($resaco,$iresaco,'l11_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitasituacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l11_sequencial = $l11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Situações da Licitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Situações da Licitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitasituacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitasituacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicitasituacao.l11_id_usuario";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitasituacao.l11_liclicita";
     $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicitasituacao.l11_licsituacao";
     $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($l11_sequencial!=null ){
         $sql2 .= " where liclicitasituacao.l11_sequencial = $l11_sequencial "; 
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
   function sql_query_file ( $l11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitasituacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($l11_sequencial!=null ){
         $sql2 .= " where liclicitasituacao.l11_sequencial = $l11_sequencial "; 
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