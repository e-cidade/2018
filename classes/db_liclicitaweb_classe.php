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
//CLASSE DA ENTIDADE liclicitaweb
class cl_liclicitaweb { 
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
   var $l29_sequencial = 0; 
   var $l29_liclicita = 0; 
   var $l29_datapublic_dia = null; 
   var $l29_datapublic_mes = null; 
   var $l29_datapublic_ano = null; 
   var $l29_datapublic = null; 
   var $l29_contato = null; 
   var $l29_email = null; 
   var $l29_telefone = null; 
   var $l29_obs = null; 
   var $l29_liberaedital = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l29_sequencial = int4 = codigo sequencial 
                 l29_liclicita = int4 = codigo da licitação 
                 l29_datapublic = date = data para publicação 
                 l29_contato = varchar(60) = Contato 
                 l29_email = varchar(100) = Email 
                 l29_telefone = varchar(15) = Telefone 
                 l29_obs = text = Observação 
                 l29_liberaedital = int4 = l29_liberaedital 
                 ";
   //funcao construtor da classe 
   function cl_liclicitaweb() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaweb"); 
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
       $this->l29_sequencial = ($this->l29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_sequencial"]:$this->l29_sequencial);
       $this->l29_liclicita = ($this->l29_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_liclicita"]:$this->l29_liclicita);
       if($this->l29_datapublic == ""){
         $this->l29_datapublic_dia = ($this->l29_datapublic_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_datapublic_dia"]:$this->l29_datapublic_dia);
         $this->l29_datapublic_mes = ($this->l29_datapublic_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_datapublic_mes"]:$this->l29_datapublic_mes);
         $this->l29_datapublic_ano = ($this->l29_datapublic_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_datapublic_ano"]:$this->l29_datapublic_ano);
         if($this->l29_datapublic_dia != ""){
            $this->l29_datapublic = $this->l29_datapublic_ano."-".$this->l29_datapublic_mes."-".$this->l29_datapublic_dia;
         }
       }
       $this->l29_contato = ($this->l29_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_contato"]:$this->l29_contato);
       $this->l29_email = ($this->l29_email == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_email"]:$this->l29_email);
       $this->l29_telefone = ($this->l29_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_telefone"]:$this->l29_telefone);
       $this->l29_obs = ($this->l29_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_obs"]:$this->l29_obs);
       $this->l29_liberaedital = ($this->l29_liberaedital == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_liberaedital"]:$this->l29_liberaedital);
     }else{
       $this->l29_sequencial = ($this->l29_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l29_sequencial"]:$this->l29_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l29_sequencial){ 
      $this->atualizacampos();
     if($this->l29_liclicita == null ){ 
       $this->erro_sql = " Campo codigo da licitação nao Informado.";
       $this->erro_campo = "l29_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l29_datapublic == null ){ 
       $this->erro_sql = " Campo data para publicação nao Informado.";
       $this->erro_campo = "l29_datapublic_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l29_liberaedital == null ){ 
       $this->erro_sql = " Campo l29_liberaedital nao Informado.";
       $this->erro_campo = "l29_liberaedital";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l29_sequencial == "" || $l29_sequencial == null ){
       $result = db_query("select nextval('liclicitaweb_l29_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaweb_l29_sequencial_seq do campo: l29_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l29_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitaweb_l29_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l29_sequencial)){
         $this->erro_sql = " Campo l29_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l29_sequencial = $l29_sequencial; 
       }
     }
     if(($this->l29_sequencial == null) || ($this->l29_sequencial == "") ){ 
       $this->erro_sql = " Campo l29_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaweb(
                                       l29_sequencial 
                                      ,l29_liclicita 
                                      ,l29_datapublic 
                                      ,l29_contato 
                                      ,l29_email 
                                      ,l29_telefone 
                                      ,l29_obs 
                                      ,l29_liberaedital 
                       )
                values (
                                $this->l29_sequencial 
                               ,$this->l29_liclicita 
                               ,".($this->l29_datapublic == "null" || $this->l29_datapublic == ""?"null":"'".$this->l29_datapublic."'")." 
                               ,'$this->l29_contato' 
                               ,'$this->l29_email' 
                               ,'$this->l29_telefone' 
                               ,'$this->l29_obs' 
                               ,$this->l29_liberaedital 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitaweb ($this->l29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitaweb já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitaweb ($this->l29_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l29_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l29_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9422,'$this->l29_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1618,9422,'','".AddSlashes(pg_result($resaco,0,'l29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9428,'','".AddSlashes(pg_result($resaco,0,'l29_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9423,'','".AddSlashes(pg_result($resaco,0,'l29_datapublic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9424,'','".AddSlashes(pg_result($resaco,0,'l29_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9426,'','".AddSlashes(pg_result($resaco,0,'l29_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9425,'','".AddSlashes(pg_result($resaco,0,'l29_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9427,'','".AddSlashes(pg_result($resaco,0,'l29_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1618,9414,'','".AddSlashes(pg_result($resaco,0,'l29_liberaedital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l29_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaweb set ";
     $virgula = "";
     if(trim($this->l29_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_sequencial"])){ 
       $sql  .= $virgula." l29_sequencial = $this->l29_sequencial ";
       $virgula = ",";
       if(trim($this->l29_sequencial) == null ){ 
         $this->erro_sql = " Campo codigo sequencial nao Informado.";
         $this->erro_campo = "l29_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l29_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_liclicita"])){ 
       $sql  .= $virgula." l29_liclicita = $this->l29_liclicita ";
       $virgula = ",";
       if(trim($this->l29_liclicita) == null ){ 
         $this->erro_sql = " Campo codigo da licitação nao Informado.";
         $this->erro_campo = "l29_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l29_datapublic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_datapublic_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l29_datapublic_dia"] !="") ){ 
       $sql  .= $virgula." l29_datapublic = '$this->l29_datapublic' ";
       $virgula = ",";
       if(trim($this->l29_datapublic) == null ){ 
         $this->erro_sql = " Campo data para publicação nao Informado.";
         $this->erro_campo = "l29_datapublic_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l29_datapublic_dia"])){ 
         $sql  .= $virgula." l29_datapublic = null ";
         $virgula = ",";
         if(trim($this->l29_datapublic) == null ){ 
           $this->erro_sql = " Campo data para publicação nao Informado.";
           $this->erro_campo = "l29_datapublic_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l29_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_contato"])){ 
       $sql  .= $virgula." l29_contato = '$this->l29_contato' ";
       $virgula = ",";
     }
     if(trim($this->l29_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_email"])){ 
       $sql  .= $virgula." l29_email = '$this->l29_email' ";
       $virgula = ",";
     }
     if(trim($this->l29_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_telefone"])){ 
       $sql  .= $virgula." l29_telefone = '$this->l29_telefone' ";
       $virgula = ",";
     }
     if(trim($this->l29_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_obs"])){ 
       $sql  .= $virgula." l29_obs = '$this->l29_obs' ";
       $virgula = ",";
     }
     if(trim($this->l29_liberaedital)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l29_liberaedital"])){ 
       $sql  .= $virgula." l29_liberaedital = $this->l29_liberaedital ";
       $virgula = ",";
       if(trim($this->l29_liberaedital) == null ){ 
         $this->erro_sql = " Campo l29_liberaedital nao Informado.";
         $this->erro_campo = "l29_liberaedital";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l29_sequencial!=null){
       $sql .= " l29_sequencial = $this->l29_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l29_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9422,'$this->l29_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1618,9422,'".AddSlashes(pg_result($resaco,$conresaco,'l29_sequencial'))."','$this->l29_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_liclicita"]))
           $resac = db_query("insert into db_acount values($acount,1618,9428,'".AddSlashes(pg_result($resaco,$conresaco,'l29_liclicita'))."','$this->l29_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_datapublic"]))
           $resac = db_query("insert into db_acount values($acount,1618,9423,'".AddSlashes(pg_result($resaco,$conresaco,'l29_datapublic'))."','$this->l29_datapublic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_contato"]))
           $resac = db_query("insert into db_acount values($acount,1618,9424,'".AddSlashes(pg_result($resaco,$conresaco,'l29_contato'))."','$this->l29_contato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_email"]))
           $resac = db_query("insert into db_acount values($acount,1618,9426,'".AddSlashes(pg_result($resaco,$conresaco,'l29_email'))."','$this->l29_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_telefone"]))
           $resac = db_query("insert into db_acount values($acount,1618,9425,'".AddSlashes(pg_result($resaco,$conresaco,'l29_telefone'))."','$this->l29_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_obs"]))
           $resac = db_query("insert into db_acount values($acount,1618,9427,'".AddSlashes(pg_result($resaco,$conresaco,'l29_obs'))."','$this->l29_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l29_liberaedital"]))
           $resac = db_query("insert into db_acount values($acount,1618,9414,'".AddSlashes(pg_result($resaco,$conresaco,'l29_liberaedital'))."','$this->l29_liberaedital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaweb nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaweb nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l29_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l29_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9422,'$l29_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1618,9422,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9428,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9423,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_datapublic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9424,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_contato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9426,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9425,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9427,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1618,9414,'','".AddSlashes(pg_result($resaco,$iresaco,'l29_liberaedital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitaweb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l29_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l29_sequencial = $l29_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaweb nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l29_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaweb nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l29_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l29_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaweb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaweb ";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaweb.l29_liclicita";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($l29_sequencial!=null ){
         $sql2 .= " where liclicitaweb.l29_sequencial = $l29_sequencial "; 
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
   function sql_query_file ( $l29_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaweb ";
     $sql2 = "";
     if($dbwhere==""){
       if($l29_sequencial!=null ){
         $sql2 .= " where liclicitaweb.l29_sequencial = $l29_sequencial "; 
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