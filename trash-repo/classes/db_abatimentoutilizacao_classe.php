<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentoutilizacao
class cl_abatimentoutilizacao { 
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
   var $k157_sequencial = 0; 
   var $k157_tipoutilizacao = null; 
   var $k157_data_dia = null; 
   var $k157_data_mes = null; 
   var $k157_data_ano = null; 
   var $k157_data = null; 
   var $k157_valor = 0; 
   var $k157_hora = null; 
   var $k157_usuario = 0; 
   var $k157_abatimento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k157_sequencial = int4 = C�digo abatimento utiliza��o 
                 k157_tipoutilizacao = char(1) = Tipo de utiliza��o 
                 k157_data = date = Data 
                 k157_valor = numeric(10) = Valor 
                 k157_hora = varchar(5) = Hora 
                 k157_usuario = int4 = Usu�rio 
                 k157_abatimento = int4 = Sequencial 
                 ";
   //funcao construtor da classe 
   function cl_abatimentoutilizacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentoutilizacao"); 
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
       $this->k157_sequencial = ($this->k157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_sequencial"]:$this->k157_sequencial);
       $this->k157_tipoutilizacao = ($this->k157_tipoutilizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_tipoutilizacao"]:$this->k157_tipoutilizacao);
       if($this->k157_data == ""){
         $this->k157_data_dia = ($this->k157_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_data_dia"]:$this->k157_data_dia);
         $this->k157_data_mes = ($this->k157_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_data_mes"]:$this->k157_data_mes);
         $this->k157_data_ano = ($this->k157_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_data_ano"]:$this->k157_data_ano);
         if($this->k157_data_dia != ""){
            $this->k157_data = $this->k157_data_ano."-".$this->k157_data_mes."-".$this->k157_data_dia;
         }
       }
       $this->k157_valor = ($this->k157_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_valor"]:$this->k157_valor);
       $this->k157_hora = ($this->k157_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_hora"]:$this->k157_hora);
       $this->k157_usuario = ($this->k157_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_usuario"]:$this->k157_usuario);
       $this->k157_abatimento = ($this->k157_abatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_abatimento"]:$this->k157_abatimento);
     }else{
       $this->k157_sequencial = ($this->k157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k157_sequencial"]:$this->k157_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k157_sequencial){ 
      $this->atualizacampos();
     if($this->k157_tipoutilizacao == null ){ 
       $this->erro_sql = " Campo Tipo de utiliza��o nao Informado.";
       $this->erro_campo = "k157_tipoutilizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k157_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k157_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k157_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k157_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k157_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k157_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k157_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "k157_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k157_abatimento == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "k157_abatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k157_sequencial == "" || $k157_sequencial == null ){
       $result = db_query("select nextval('abatimentoutilizacao_k157_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentoutilizacao_k157_sequencial_seq do campo: k157_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k157_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from abatimentoutilizacao_k157_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k157_sequencial)){
         $this->erro_sql = " Campo k157_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k157_sequencial = $k157_sequencial; 
       }
     }
     if(($this->k157_sequencial == null) || ($this->k157_sequencial == "") ){ 
       $this->erro_sql = " Campo k157_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentoutilizacao(
                                       k157_sequencial 
                                      ,k157_tipoutilizacao 
                                      ,k157_data 
                                      ,k157_valor 
                                      ,k157_hora 
                                      ,k157_usuario 
                                      ,k157_abatimento 
                       )
                values (
                                $this->k157_sequencial 
                               ,'$this->k157_tipoutilizacao' 
                               ,".($this->k157_data == "null" || $this->k157_data == ""?"null":"'".$this->k157_data."'")." 
                               ,$this->k157_valor 
                               ,'$this->k157_hora' 
                               ,$this->k157_usuario 
                               ,$this->k157_abatimento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Abatimento Utiliza��o ($this->k157_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Abatimento Utiliza��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Abatimento Utiliza��o ($this->k157_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k157_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k157_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19606,'$this->k157_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3484,19606,'','".AddSlashes(pg_result($resaco,0,'k157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19607,'','".AddSlashes(pg_result($resaco,0,'k157_tipoutilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19608,'','".AddSlashes(pg_result($resaco,0,'k157_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19609,'','".AddSlashes(pg_result($resaco,0,'k157_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19610,'','".AddSlashes(pg_result($resaco,0,'k157_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19611,'','".AddSlashes(pg_result($resaco,0,'k157_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3484,19631,'','".AddSlashes(pg_result($resaco,0,'k157_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k157_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentoutilizacao set ";
     $virgula = "";
     if(trim($this->k157_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_sequencial"])){ 
       $sql  .= $virgula." k157_sequencial = $this->k157_sequencial ";
       $virgula = ",";
       if(trim($this->k157_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo abatimento utiliza��o nao Informado.";
         $this->erro_campo = "k157_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k157_tipoutilizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_tipoutilizacao"])){ 
       $sql  .= $virgula." k157_tipoutilizacao = '$this->k157_tipoutilizacao' ";
       $virgula = ",";
       if(trim($this->k157_tipoutilizacao) == null ){ 
         $this->erro_sql = " Campo Tipo de utiliza��o nao Informado.";
         $this->erro_campo = "k157_tipoutilizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k157_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k157_data_dia"] !="") ){ 
       $sql  .= $virgula." k157_data = '$this->k157_data' ";
       $virgula = ",";
       if(trim($this->k157_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k157_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k157_data_dia"])){ 
         $sql  .= $virgula." k157_data = null ";
         $virgula = ",";
         if(trim($this->k157_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k157_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k157_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_valor"])){ 
       $sql  .= $virgula." k157_valor = $this->k157_valor ";
       $virgula = ",";
       if(trim($this->k157_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k157_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k157_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_hora"])){ 
       $sql  .= $virgula." k157_hora = '$this->k157_hora' ";
       $virgula = ",";
       if(trim($this->k157_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k157_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k157_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_usuario"])){ 
       $sql  .= $virgula." k157_usuario = $this->k157_usuario ";
       $virgula = ",";
       if(trim($this->k157_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "k157_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k157_abatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k157_abatimento"])){ 
       $sql  .= $virgula." k157_abatimento = $this->k157_abatimento ";
       $virgula = ",";
       if(trim($this->k157_abatimento) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k157_abatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k157_sequencial!=null){
       $sql .= " k157_sequencial = $this->k157_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k157_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19606,'$this->k157_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_sequencial"]) || $this->k157_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3484,19606,'".AddSlashes(pg_result($resaco,$conresaco,'k157_sequencial'))."','$this->k157_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_tipoutilizacao"]) || $this->k157_tipoutilizacao != "")
           $resac = db_query("insert into db_acount values($acount,3484,19607,'".AddSlashes(pg_result($resaco,$conresaco,'k157_tipoutilizacao'))."','$this->k157_tipoutilizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_data"]) || $this->k157_data != "")
           $resac = db_query("insert into db_acount values($acount,3484,19608,'".AddSlashes(pg_result($resaco,$conresaco,'k157_data'))."','$this->k157_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_valor"]) || $this->k157_valor != "")
           $resac = db_query("insert into db_acount values($acount,3484,19609,'".AddSlashes(pg_result($resaco,$conresaco,'k157_valor'))."','$this->k157_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_hora"]) || $this->k157_hora != "")
           $resac = db_query("insert into db_acount values($acount,3484,19610,'".AddSlashes(pg_result($resaco,$conresaco,'k157_hora'))."','$this->k157_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_usuario"]) || $this->k157_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3484,19611,'".AddSlashes(pg_result($resaco,$conresaco,'k157_usuario'))."','$this->k157_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k157_abatimento"]) || $this->k157_abatimento != "")
           $resac = db_query("insert into db_acount values($acount,3484,19631,'".AddSlashes(pg_result($resaco,$conresaco,'k157_abatimento'))."','$this->k157_abatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Utiliza��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k157_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Utiliza��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k157_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k157_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19606,'$k157_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3484,19606,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19607,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_tipoutilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19608,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19609,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19610,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19611,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3484,19631,'','".AddSlashes(pg_result($resaco,$iresaco,'k157_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from abatimentoutilizacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k157_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k157_sequencial = $k157_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abatimento Utiliza��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k157_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abatimento Utiliza��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:abatimentoutilizacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k157_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoutilizacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimentoutilizacao.k157_usuario";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentoutilizacao.k157_abatimento";
     $sql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
     $sql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
     $sql2 = "";
     if($dbwhere==""){
       if($k157_sequencial!=null ){
         $sql2 .= " where abatimentoutilizacao.k157_sequencial = $k157_sequencial "; 
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
   function sql_query_file ( $k157_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoutilizacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k157_sequencial!=null ){
         $sql2 .= " where abatimentoutilizacao.k157_sequencial = $k157_sequencial "; 
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