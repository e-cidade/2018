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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdisbanco
class cl_cancdisbanco { 
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
   var $k124_sequencial = 0; 
   var $k124_usuario = 0; 
   var $k124_data_dia = null; 
   var $k124_data_mes = null; 
   var $k124_data_ano = null; 
   var $k124_data = null; 
   var $k124_ip = null; 
   var $k124_codret = 0; 
   var $k124_codcla = 0; 
   var $k124_nomearq = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k124_sequencial = int4 = Sequencial 
                 k124_usuario = int8 = Usuário 
                 k124_data = date = Data 
                 k124_ip = char(15) = Ip 
                 k124_codret = int8 = Código de Retorno 
                 k124_codcla = int8 = Código de Classificação 
                 k124_nomearq = varchar(40) = Nome do Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_cancdisbanco() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdisbanco"); 
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
       $this->k124_sequencial = ($this->k124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_sequencial"]:$this->k124_sequencial);
       $this->k124_usuario = ($this->k124_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_usuario"]:$this->k124_usuario);
       if($this->k124_data == ""){
         $this->k124_data_dia = ($this->k124_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_data_dia"]:$this->k124_data_dia);
         $this->k124_data_mes = ($this->k124_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_data_mes"]:$this->k124_data_mes);
         $this->k124_data_ano = ($this->k124_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_data_ano"]:$this->k124_data_ano);
         if($this->k124_data_dia != ""){
            $this->k124_data = $this->k124_data_ano."-".$this->k124_data_mes."-".$this->k124_data_dia;
         }
       }
       $this->k124_ip = ($this->k124_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_ip"]:$this->k124_ip);
       $this->k124_codret = ($this->k124_codret == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_codret"]:$this->k124_codret);
       $this->k124_codcla = ($this->k124_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_codcla"]:$this->k124_codcla);
       $this->k124_nomearq = ($this->k124_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_nomearq"]:$this->k124_nomearq);
     }else{
       $this->k124_sequencial = ($this->k124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k124_sequencial"]:$this->k124_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k124_sequencial){ 
      $this->atualizacampos();
     if($this->k124_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k124_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k124_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k124_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k124_ip == null ){ 
       $this->erro_sql = " Campo Ip nao Informado.";
       $this->erro_campo = "k124_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k124_codret == null ){ 
       $this->erro_sql = " Campo Código de Retorno nao Informado.";
       $this->erro_campo = "k124_codret";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k124_codcla == null ){ 
       $this->erro_sql = " Campo Código de Classificação nao Informado.";
       $this->erro_campo = "k124_codcla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k124_nomearq == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
       $this->erro_campo = "k124_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k124_sequencial == "" || $k124_sequencial == null ){
       $result = db_query("select nextval('cancdisbanco_k124_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdisbanco_k124_sequencial_seq do campo: k124_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k124_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdisbanco_k124_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k124_sequencial)){
         $this->erro_sql = " Campo k124_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k124_sequencial = $k124_sequencial; 
       }
     }
     if(($this->k124_sequencial == null) || ($this->k124_sequencial == "") ){ 
       $this->erro_sql = " Campo k124_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdisbanco(
                                       k124_sequencial 
                                      ,k124_usuario 
                                      ,k124_data 
                                      ,k124_ip 
                                      ,k124_codret 
                                      ,k124_codcla 
                                      ,k124_nomearq 
                       )
                values (
                                $this->k124_sequencial 
                               ,$this->k124_usuario 
                               ,".($this->k124_data == "null" || $this->k124_data == ""?"null":"'".$this->k124_data."'")." 
                               ,'$this->k124_ip' 
                               ,$this->k124_codret 
                               ,$this->k124_codcla 
                               ,'$this->k124_nomearq' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cancelamento Baixa Banco ($this->k124_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cancelamento Baixa Banco já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cancelamento Baixa Banco ($this->k124_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k124_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k124_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17948,'$this->k124_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3172,17948,'','".AddSlashes(pg_result($resaco,0,'k124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17949,'','".AddSlashes(pg_result($resaco,0,'k124_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17950,'','".AddSlashes(pg_result($resaco,0,'k124_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17951,'','".AddSlashes(pg_result($resaco,0,'k124_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17952,'','".AddSlashes(pg_result($resaco,0,'k124_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17953,'','".AddSlashes(pg_result($resaco,0,'k124_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3172,17954,'','".AddSlashes(pg_result($resaco,0,'k124_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k124_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cancdisbanco set ";
     $virgula = "";
     if(trim($this->k124_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_sequencial"])){ 
       $sql  .= $virgula." k124_sequencial = $this->k124_sequencial ";
       $virgula = ",";
       if(trim($this->k124_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k124_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k124_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_usuario"])){ 
       $sql  .= $virgula." k124_usuario = $this->k124_usuario ";
       $virgula = ",";
       if(trim($this->k124_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k124_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k124_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k124_data_dia"] !="") ){ 
       $sql  .= $virgula." k124_data = '$this->k124_data' ";
       $virgula = ",";
       if(trim($this->k124_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k124_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k124_data_dia"])){ 
         $sql  .= $virgula." k124_data = null ";
         $virgula = ",";
         if(trim($this->k124_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k124_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k124_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_ip"])){ 
       $sql  .= $virgula." k124_ip = '$this->k124_ip' ";
       $virgula = ",";
       if(trim($this->k124_ip) == null ){ 
         $this->erro_sql = " Campo Ip nao Informado.";
         $this->erro_campo = "k124_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k124_codret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_codret"])){ 
       $sql  .= $virgula." k124_codret = $this->k124_codret ";
       $virgula = ",";
       if(trim($this->k124_codret) == null ){ 
         $this->erro_sql = " Campo Código de Retorno nao Informado.";
         $this->erro_campo = "k124_codret";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k124_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_codcla"])){ 
       $sql  .= $virgula." k124_codcla = $this->k124_codcla ";
       $virgula = ",";
       if(trim($this->k124_codcla) == null ){ 
         $this->erro_sql = " Campo Código de Classificação nao Informado.";
         $this->erro_campo = "k124_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k124_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k124_nomearq"])){ 
       $sql  .= $virgula." k124_nomearq = '$this->k124_nomearq' ";
       $virgula = ",";
       if(trim($this->k124_nomearq) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo nao Informado.";
         $this->erro_campo = "k124_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k124_sequencial!=null){
       $sql .= " k124_sequencial = $this->k124_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k124_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17948,'$this->k124_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_sequencial"]) || $this->k124_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3172,17948,'".AddSlashes(pg_result($resaco,$conresaco,'k124_sequencial'))."','$this->k124_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_usuario"]) || $this->k124_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3172,17949,'".AddSlashes(pg_result($resaco,$conresaco,'k124_usuario'))."','$this->k124_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_data"]) || $this->k124_data != "")
           $resac = db_query("insert into db_acount values($acount,3172,17950,'".AddSlashes(pg_result($resaco,$conresaco,'k124_data'))."','$this->k124_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_ip"]) || $this->k124_ip != "")
           $resac = db_query("insert into db_acount values($acount,3172,17951,'".AddSlashes(pg_result($resaco,$conresaco,'k124_ip'))."','$this->k124_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_codret"]) || $this->k124_codret != "")
           $resac = db_query("insert into db_acount values($acount,3172,17952,'".AddSlashes(pg_result($resaco,$conresaco,'k124_codret'))."','$this->k124_codret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_codcla"]) || $this->k124_codcla != "")
           $resac = db_query("insert into db_acount values($acount,3172,17953,'".AddSlashes(pg_result($resaco,$conresaco,'k124_codcla'))."','$this->k124_codcla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k124_nomearq"]) || $this->k124_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,3172,17954,'".AddSlashes(pg_result($resaco,$conresaco,'k124_nomearq'))."','$this->k124_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento Baixa Banco nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento Baixa Banco nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k124_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k124_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17948,'$k124_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3172,17948,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17949,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17950,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17951,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17952,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_codret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17953,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_codcla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3172,17954,'','".AddSlashes(pg_result($resaco,$iresaco,'k124_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdisbanco
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k124_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k124_sequencial = $k124_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cancelamento Baixa Banco nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cancelamento Baixa Banco nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k124_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdisbanco";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdisbanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($k124_sequencial!=null ){
         $sql2 .= " where cancdisbanco.k124_sequencial = $k124_sequencial "; 
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
   function sql_query_file ( $k124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdisbanco ";
     $sql2 = "";
     if($dbwhere==""){
       if($k124_sequencial!=null ){
         $sql2 .= " where cancdisbanco.k124_sequencial = $k124_sequencial "; 
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