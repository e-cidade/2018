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

//MODULO: caixa
//CLASSE DA ENTIDADE arredesconto
class cl_arredesconto { 
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
   var $k38_sequencial = 0; 
   var $k38_cadtipoparc = 0; 
   var $k38_usuario = 0; 
   var $k38_numpre = 0; 
   var $k38_data_dia = null; 
   var $k38_data_mes = null; 
   var $k38_data_ano = null; 
   var $k38_data = null; 
   var $k38_hora = null; 
   var $k38_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k38_sequencial = int4 = Sequencial 
                 k38_cadtipoparc = int4 = Regra Desconto 
                 k38_usuario = int4 = Usuário 
                 k38_numpre = int4 = Numpre 
                 k38_data = date = Data 
                 k38_hora = char(5) = Hora 
                 k38_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_arredesconto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arredesconto"); 
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
       $this->k38_sequencial = ($this->k38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_sequencial"]:$this->k38_sequencial);
       $this->k38_cadtipoparc = ($this->k38_cadtipoparc == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_cadtipoparc"]:$this->k38_cadtipoparc);
       $this->k38_usuario = ($this->k38_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_usuario"]:$this->k38_usuario);
       $this->k38_numpre = ($this->k38_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_numpre"]:$this->k38_numpre);
       if($this->k38_data == ""){
         $this->k38_data_dia = ($this->k38_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_data_dia"]:$this->k38_data_dia);
         $this->k38_data_mes = ($this->k38_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_data_mes"]:$this->k38_data_mes);
         $this->k38_data_ano = ($this->k38_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_data_ano"]:$this->k38_data_ano);
         if($this->k38_data_dia != ""){
            $this->k38_data = $this->k38_data_ano."-".$this->k38_data_mes."-".$this->k38_data_dia;
         }
       }
       $this->k38_hora = ($this->k38_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_hora"]:$this->k38_hora);
       $this->k38_obs = ($this->k38_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_obs"]:$this->k38_obs);
     }else{
       $this->k38_sequencial = ($this->k38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k38_sequencial"]:$this->k38_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k38_sequencial){ 
      $this->atualizacampos();
     if($this->k38_cadtipoparc == null ){ 
       $this->erro_sql = " Campo Regra Desconto nao Informado.";
       $this->erro_campo = "k38_cadtipoparc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k38_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k38_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k38_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k38_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k38_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k38_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k38_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k38_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k38_sequencial == "" || $k38_sequencial == null ){
       $result = db_query("select nextval('arredesconto_k38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arredesconto_k38_sequencial_seq do campo: k38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arredesconto_k38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k38_sequencial)){
         $this->erro_sql = " Campo k38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k38_sequencial = $k38_sequencial; 
       }
     }
     if(($this->k38_sequencial == null) || ($this->k38_sequencial == "") ){ 
       $this->erro_sql = " Campo k38_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arredesconto(
                                       k38_sequencial 
                                      ,k38_cadtipoparc 
                                      ,k38_usuario 
                                      ,k38_numpre 
                                      ,k38_data 
                                      ,k38_hora 
                                      ,k38_obs 
                       )
                values (
                                $this->k38_sequencial 
                               ,$this->k38_cadtipoparc 
                               ,$this->k38_usuario 
                               ,$this->k38_numpre 
                               ,".($this->k38_data == "null" || $this->k38_data == ""?"null":"'".$this->k38_data."'")." 
                               ,'$this->k38_hora' 
                               ,'$this->k38_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arredesconto ($this->k38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arredesconto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arredesconto ($this->k38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k38_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10779,'$this->k38_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1855,10779,'','".AddSlashes(pg_result($resaco,0,'k38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10780,'','".AddSlashes(pg_result($resaco,0,'k38_cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10781,'','".AddSlashes(pg_result($resaco,0,'k38_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10782,'','".AddSlashes(pg_result($resaco,0,'k38_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10783,'','".AddSlashes(pg_result($resaco,0,'k38_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10784,'','".AddSlashes(pg_result($resaco,0,'k38_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1855,10785,'','".AddSlashes(pg_result($resaco,0,'k38_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k38_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update arredesconto set ";
     $virgula = "";
     if(trim($this->k38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_sequencial"])){ 
       $sql  .= $virgula." k38_sequencial = $this->k38_sequencial ";
       $virgula = ",";
       if(trim($this->k38_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k38_cadtipoparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_cadtipoparc"])){ 
       $sql  .= $virgula." k38_cadtipoparc = $this->k38_cadtipoparc ";
       $virgula = ",";
       if(trim($this->k38_cadtipoparc) == null ){ 
         $this->erro_sql = " Campo Regra Desconto nao Informado.";
         $this->erro_campo = "k38_cadtipoparc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k38_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_usuario"])){ 
       $sql  .= $virgula." k38_usuario = $this->k38_usuario ";
       $virgula = ",";
       if(trim($this->k38_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k38_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k38_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_numpre"])){ 
       $sql  .= $virgula." k38_numpre = $this->k38_numpre ";
       $virgula = ",";
       if(trim($this->k38_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k38_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k38_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k38_data_dia"] !="") ){ 
       $sql  .= $virgula." k38_data = '$this->k38_data' ";
       $virgula = ",";
       if(trim($this->k38_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k38_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k38_data_dia"])){ 
         $sql  .= $virgula." k38_data = null ";
         $virgula = ",";
         if(trim($this->k38_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k38_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k38_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_hora"])){ 
       $sql  .= $virgula." k38_hora = '$this->k38_hora' ";
       $virgula = ",";
       if(trim($this->k38_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k38_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k38_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k38_obs"])){ 
       $sql  .= $virgula." k38_obs = '$this->k38_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k38_sequencial!=null){
       $sql .= " k38_sequencial = $this->k38_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k38_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10779,'$this->k38_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1855,10779,'".AddSlashes(pg_result($resaco,$conresaco,'k38_sequencial'))."','$this->k38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_cadtipoparc"]))
           $resac = db_query("insert into db_acount values($acount,1855,10780,'".AddSlashes(pg_result($resaco,$conresaco,'k38_cadtipoparc'))."','$this->k38_cadtipoparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1855,10781,'".AddSlashes(pg_result($resaco,$conresaco,'k38_usuario'))."','$this->k38_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1855,10782,'".AddSlashes(pg_result($resaco,$conresaco,'k38_numpre'))."','$this->k38_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_data"]))
           $resac = db_query("insert into db_acount values($acount,1855,10783,'".AddSlashes(pg_result($resaco,$conresaco,'k38_data'))."','$this->k38_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_hora"]))
           $resac = db_query("insert into db_acount values($acount,1855,10784,'".AddSlashes(pg_result($resaco,$conresaco,'k38_hora'))."','$this->k38_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k38_obs"]))
           $resac = db_query("insert into db_acount values($acount,1855,10785,'".AddSlashes(pg_result($resaco,$conresaco,'k38_obs'))."','$this->k38_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arredesconto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arredesconto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k38_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k38_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10779,'$k38_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1855,10779,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10780,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_cadtipoparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10781,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10782,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10783,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10784,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1855,10785,'','".AddSlashes(pg_result($resaco,$iresaco,'k38_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arredesconto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k38_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k38_sequencial = $k38_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arredesconto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arredesconto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arredesconto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>