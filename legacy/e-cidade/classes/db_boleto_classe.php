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
//CLASSE DA ENTIDADE boleto
class cl_boleto { 
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
   var $k139_sequencial = 0; 
   var $k139_data_dia = null; 
   var $k139_data_mes = null; 
   var $k139_data_ano = null; 
   var $k139_data = null; 
   var $k139_hora = null; 
   var $k139_usuario = 0; 
   var $k139_conveniocobranca = 0; 
   var $K139_regraemissao = 0; 
   var $k139_codigobarras = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k139_sequencial = int4 = Sequencial 
                 k139_data = date = Data 
                 k139_hora = varchar(5) = Hora 
                 k139_usuario = int4 = Usuário 
                 k139_conveniocobranca = int4 = Convenio 
                 K139_regraemissao = int4 = Regra de emissão 
                 k139_codigobarras = varchar(100) = Código de barras 
                 ";
   //funcao construtor da classe 
   function cl_boleto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("boleto"); 
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
       $this->k139_sequencial = ($this->k139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_sequencial"]:$this->k139_sequencial);
       if($this->k139_data == ""){
         $this->k139_data_dia = ($this->k139_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_data_dia"]:$this->k139_data_dia);
         $this->k139_data_mes = ($this->k139_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_data_mes"]:$this->k139_data_mes);
         $this->k139_data_ano = ($this->k139_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_data_ano"]:$this->k139_data_ano);
         if($this->k139_data_dia != ""){
            $this->k139_data = $this->k139_data_ano."-".$this->k139_data_mes."-".$this->k139_data_dia;
         }
       }
       $this->k139_hora = ($this->k139_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_hora"]:$this->k139_hora);
       $this->k139_usuario = ($this->k139_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_usuario"]:$this->k139_usuario);
       $this->k139_conveniocobranca = ($this->k139_conveniocobranca == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_conveniocobranca"]:$this->k139_conveniocobranca);
       $this->K139_regraemissao = ($this->K139_regraemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["K139_regraemissao"]:$this->K139_regraemissao);
       $this->k139_codigobarras = ($this->k139_codigobarras == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_codigobarras"]:$this->k139_codigobarras);
     }else{
       $this->k139_sequencial = ($this->k139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k139_sequencial"]:$this->k139_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k139_sequencial){ 
      $this->atualizacampos();
     if($this->k139_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k139_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k139_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k139_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k139_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k139_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k139_conveniocobranca == null ){ 
       $this->erro_sql = " Campo Convenio nao Informado.";
       $this->erro_campo = "k139_conveniocobranca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->K139_regraemissao == null ){ 
       $this->erro_sql = " Campo Regra de emissão nao Informado.";
       $this->erro_campo = "K139_regraemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k139_codigobarras == null ){ 
       $this->erro_sql = " Campo Código de barras nao Informado.";
       $this->erro_campo = "k139_codigobarras";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k139_sequencial == "" || $k139_sequencial == null ){
       $result = db_query("select nextval('boleto_k139_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: boleto_k139_sequencial_seq do campo: k139_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k139_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from boleto_k139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k139_sequencial)){
         $this->erro_sql = " Campo k139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k139_sequencial = $k139_sequencial; 
       }
     }
     if(($this->k139_sequencial == null) || ($this->k139_sequencial == "") ){ 
       $this->erro_sql = " Campo k139_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into boleto(
                                       k139_sequencial 
                                      ,k139_data 
                                      ,k139_hora 
                                      ,k139_usuario 
                                      ,k139_conveniocobranca 
                                      ,K139_regraemissao 
                                      ,k139_codigobarras 
                       )
                values (
                                $this->k139_sequencial 
                               ,".($this->k139_data == "null" || $this->k139_data == ""?"null":"'".$this->k139_data."'")." 
                               ,'$this->k139_hora' 
                               ,$this->k139_usuario 
                               ,$this->k139_conveniocobranca 
                               ,$this->K139_regraemissao 
                               ,'$this->k139_codigobarras' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Boleto ($this->k139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Boleto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Boleto ($this->k139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k139_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18896,'$this->k139_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3351,18896,'','".AddSlashes(pg_result($resaco,0,'k139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18897,'','".AddSlashes(pg_result($resaco,0,'k139_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18898,'','".AddSlashes(pg_result($resaco,0,'k139_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18899,'','".AddSlashes(pg_result($resaco,0,'k139_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18900,'','".AddSlashes(pg_result($resaco,0,'k139_conveniocobranca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18901,'','".AddSlashes(pg_result($resaco,0,'K139_regraemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3351,18902,'','".AddSlashes(pg_result($resaco,0,'k139_codigobarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k139_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update boleto set ";
     $virgula = "";
     if(trim($this->k139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_sequencial"])){ 
       $sql  .= $virgula." k139_sequencial = $this->k139_sequencial ";
       $virgula = ",";
       if(trim($this->k139_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k139_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k139_data_dia"] !="") ){ 
       $sql  .= $virgula." k139_data = '$this->k139_data' ";
       $virgula = ",";
       if(trim($this->k139_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k139_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k139_data_dia"])){ 
         $sql  .= $virgula." k139_data = null ";
         $virgula = ",";
         if(trim($this->k139_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k139_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k139_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_hora"])){ 
       $sql  .= $virgula." k139_hora = '$this->k139_hora' ";
       $virgula = ",";
       if(trim($this->k139_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k139_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k139_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_usuario"])){ 
       $sql  .= $virgula." k139_usuario = $this->k139_usuario ";
       $virgula = ",";
       if(trim($this->k139_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k139_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k139_conveniocobranca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_conveniocobranca"])){ 
       $sql  .= $virgula." k139_conveniocobranca = $this->k139_conveniocobranca ";
       $virgula = ",";
       if(trim($this->k139_conveniocobranca) == null ){ 
         $this->erro_sql = " Campo Convenio nao Informado.";
         $this->erro_campo = "k139_conveniocobranca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->K139_regraemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["K139_regraemissao"])){ 
       $sql  .= $virgula." K139_regraemissao = $this->K139_regraemissao ";
       $virgula = ",";
       if(trim($this->K139_regraemissao) == null ){ 
         $this->erro_sql = " Campo Regra de emissão nao Informado.";
         $this->erro_campo = "K139_regraemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k139_codigobarras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k139_codigobarras"])){ 
       $sql  .= $virgula." k139_codigobarras = '$this->k139_codigobarras' ";
       $virgula = ",";
       if(trim($this->k139_codigobarras) == null ){ 
         $this->erro_sql = " Campo Código de barras nao Informado.";
         $this->erro_campo = "k139_codigobarras";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k139_sequencial!=null){
       $sql .= " k139_sequencial = $this->k139_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k139_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18896,'$this->k139_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_sequencial"]) || $this->k139_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3351,18896,'".AddSlashes(pg_result($resaco,$conresaco,'k139_sequencial'))."','$this->k139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_data"]) || $this->k139_data != "")
           $resac = db_query("insert into db_acount values($acount,3351,18897,'".AddSlashes(pg_result($resaco,$conresaco,'k139_data'))."','$this->k139_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_hora"]) || $this->k139_hora != "")
           $resac = db_query("insert into db_acount values($acount,3351,18898,'".AddSlashes(pg_result($resaco,$conresaco,'k139_hora'))."','$this->k139_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_usuario"]) || $this->k139_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3351,18899,'".AddSlashes(pg_result($resaco,$conresaco,'k139_usuario'))."','$this->k139_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_conveniocobranca"]) || $this->k139_conveniocobranca != "")
           $resac = db_query("insert into db_acount values($acount,3351,18900,'".AddSlashes(pg_result($resaco,$conresaco,'k139_conveniocobranca'))."','$this->k139_conveniocobranca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["K139_regraemissao"]) || $this->K139_regraemissao != "")
           $resac = db_query("insert into db_acount values($acount,3351,18901,'".AddSlashes(pg_result($resaco,$conresaco,'K139_regraemissao'))."','$this->K139_regraemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k139_codigobarras"]) || $this->k139_codigobarras != "")
           $resac = db_query("insert into db_acount values($acount,3351,18902,'".AddSlashes(pg_result($resaco,$conresaco,'k139_codigobarras'))."','$this->k139_codigobarras',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boleto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boleto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k139_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k139_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18896,'$k139_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3351,18896,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18897,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18898,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18899,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18900,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_conveniocobranca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18901,'','".AddSlashes(pg_result($resaco,$iresaco,'K139_regraemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3351,18902,'','".AddSlashes(pg_result($resaco,$iresaco,'k139_codigobarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from boleto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k139_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k139_sequencial = $k139_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boleto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boleto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:boleto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from boleto ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = boleto.k139_usuario";
     $sql .= "      inner join modcarnepadrao  on  modcarnepadrao.k48_sequencial = boleto.K139_regraemissao";
     $sql .= "      inner join conveniocobranca  on  conveniocobranca.ar13_sequencial = boleto.k139_conveniocobranca";
     $sql .= "      inner join db_config  on  db_config.codigo = modcarnepadrao.k48_instit";
     $sql .= "      inner join cadtipomod  on  cadtipomod.k46_sequencial = modcarnepadrao.k48_cadtipomod";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = modcarnepadrao.k48_cadconvenio";
     $sql .= "      inner join cadconvenio  as a on   a.ar11_sequencial = conveniocobranca.ar13_cadconvenio";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = conveniocobranca.ar13_bancoagencia";
     $sql2 = "";
     if($dbwhere==""){
       if($k139_sequencial!=null ){
         $sql2 .= " where boleto.k139_sequencial = $k139_sequencial "; 
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
   function sql_query_file ( $k139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from boleto ";
     $sql2 = "";
     if($dbwhere==""){
       if($k139_sequencial!=null ){
         $sql2 .= " where boleto.k139_sequencial = $k139_sequencial "; 
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