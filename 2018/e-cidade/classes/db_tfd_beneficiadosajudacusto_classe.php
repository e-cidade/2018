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

//MODULO: tfd
//CLASSE DA ENTIDADE tfd_beneficiadosajudacusto
class cl_tfd_beneficiadosajudacusto { 
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
   var $tf15_i_codigo = 0; 
   var $tf15_i_cgsund = 0; 
   var $tf15_i_ajudacusto = 0; 
   var $tf15_i_ajudacustopedido = 0; 
   var $tf15_f_valoremitido = 0; 
   var $tf15_d_data_dia = null; 
   var $tf15_d_data_mes = null; 
   var $tf15_d_data_ano = null; 
   var $tf15_d_data = null; 
   var $tf15_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf15_i_codigo = int4 = Código 
                 tf15_i_cgsund = int4 = CGS 
                 tf15_i_ajudacusto = int4 = Ajuda 
                 tf15_i_ajudacustopedido = int4 = Ajuda Pedido 
                 tf15_f_valoremitido = float4 = Valor 
                 tf15_d_data = date = Data 
                 tf15_observacao = varchar(100) = Observação 
                 ";
   //funcao construtor da classe 
   function cl_tfd_beneficiadosajudacusto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_beneficiadosajudacusto"); 
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
       $this->tf15_i_codigo = ($this->tf15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_i_codigo"]:$this->tf15_i_codigo);
       $this->tf15_i_cgsund = ($this->tf15_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_i_cgsund"]:$this->tf15_i_cgsund);
       $this->tf15_i_ajudacusto = ($this->tf15_i_ajudacusto == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacusto"]:$this->tf15_i_ajudacusto);
       $this->tf15_i_ajudacustopedido = ($this->tf15_i_ajudacustopedido == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacustopedido"]:$this->tf15_i_ajudacustopedido);
       $this->tf15_f_valoremitido = ($this->tf15_f_valoremitido == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_f_valoremitido"]:$this->tf15_f_valoremitido);
       if($this->tf15_d_data == ""){
         $this->tf15_d_data_dia = ($this->tf15_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_d_data_dia"]:$this->tf15_d_data_dia);
         $this->tf15_d_data_mes = ($this->tf15_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_d_data_mes"]:$this->tf15_d_data_mes);
         $this->tf15_d_data_ano = ($this->tf15_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_d_data_ano"]:$this->tf15_d_data_ano);
         if($this->tf15_d_data_dia != ""){
            $this->tf15_d_data = $this->tf15_d_data_ano."-".$this->tf15_d_data_mes."-".$this->tf15_d_data_dia;
         }
       }
       $this->tf15_observacao = ($this->tf15_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_observacao"]:$this->tf15_observacao);
     }else{
       $this->tf15_i_codigo = ($this->tf15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf15_i_codigo"]:$this->tf15_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf15_i_codigo){ 
      $this->atualizacampos();
     if($this->tf15_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "tf15_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf15_i_ajudacusto == null ){ 
       $this->erro_sql = " Campo Ajuda nao Informado.";
       $this->erro_campo = "tf15_i_ajudacusto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf15_i_ajudacustopedido == null ){ 
       $this->erro_sql = " Campo Ajuda Pedido nao Informado.";
       $this->erro_campo = "tf15_i_ajudacustopedido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf15_f_valoremitido == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "tf15_f_valoremitido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf15_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf15_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf15_i_codigo == "" || $tf15_i_codigo == null ){
       $result = db_query("select nextval('tfd_beneficiadosajudacusto_tf15_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_beneficiadosajudacusto_tf15_i_codigo_seq do campo: tf15_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf15_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_beneficiadosajudacusto_tf15_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf15_i_codigo)){
         $this->erro_sql = " Campo tf15_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf15_i_codigo = $tf15_i_codigo; 
       }
     }
     if(($this->tf15_i_codigo == null) || ($this->tf15_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf15_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_beneficiadosajudacusto(
                                       tf15_i_codigo 
                                      ,tf15_i_cgsund 
                                      ,tf15_i_ajudacusto 
                                      ,tf15_i_ajudacustopedido 
                                      ,tf15_f_valoremitido 
                                      ,tf15_d_data 
                                      ,tf15_observacao 
                       )
                values (
                                $this->tf15_i_codigo 
                               ,$this->tf15_i_cgsund 
                               ,$this->tf15_i_ajudacusto 
                               ,$this->tf15_i_ajudacustopedido 
                               ,$this->tf15_f_valoremitido 
                               ,".($this->tf15_d_data == "null" || $this->tf15_d_data == ""?"null":"'".$this->tf15_d_data."'")." 
                               ,'$this->tf15_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_beneficiadosajudacusto ($this->tf15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_beneficiadosajudacusto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_beneficiadosajudacusto ($this->tf15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf15_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf15_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16388,'$this->tf15_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2871,16388,'','".AddSlashes(pg_result($resaco,0,'tf15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,16390,'','".AddSlashes(pg_result($resaco,0,'tf15_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,16389,'','".AddSlashes(pg_result($resaco,0,'tf15_i_ajudacusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,16391,'','".AddSlashes(pg_result($resaco,0,'tf15_i_ajudacustopedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,16392,'','".AddSlashes(pg_result($resaco,0,'tf15_f_valoremitido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,16393,'','".AddSlashes(pg_result($resaco,0,'tf15_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2871,18275,'','".AddSlashes(pg_result($resaco,0,'tf15_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf15_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_beneficiadosajudacusto set ";
     $virgula = "";
     if(trim($this->tf15_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_codigo"])){ 
       $sql  .= $virgula." tf15_i_codigo = $this->tf15_i_codigo ";
       $virgula = ",";
       if(trim($this->tf15_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf15_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf15_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_cgsund"])){ 
       $sql  .= $virgula." tf15_i_cgsund = $this->tf15_i_cgsund ";
       $virgula = ",";
       if(trim($this->tf15_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "tf15_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf15_i_ajudacusto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacusto"])){ 
       $sql  .= $virgula." tf15_i_ajudacusto = $this->tf15_i_ajudacusto ";
       $virgula = ",";
       if(trim($this->tf15_i_ajudacusto) == null ){ 
         $this->erro_sql = " Campo Ajuda nao Informado.";
         $this->erro_campo = "tf15_i_ajudacusto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf15_i_ajudacustopedido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacustopedido"])){ 
       $sql  .= $virgula." tf15_i_ajudacustopedido = $this->tf15_i_ajudacustopedido ";
       $virgula = ",";
       if(trim($this->tf15_i_ajudacustopedido) == null ){ 
         $this->erro_sql = " Campo Ajuda Pedido nao Informado.";
         $this->erro_campo = "tf15_i_ajudacustopedido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf15_f_valoremitido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_f_valoremitido"])){ 
       $sql  .= $virgula." tf15_f_valoremitido = $this->tf15_f_valoremitido ";
       $virgula = ",";
       if(trim($this->tf15_f_valoremitido) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "tf15_f_valoremitido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf15_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf15_d_data_dia"] !="") ){ 
       $sql  .= $virgula." tf15_d_data = '$this->tf15_d_data' ";
       $virgula = ",";
       if(trim($this->tf15_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf15_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_d_data_dia"])){ 
         $sql  .= $virgula." tf15_d_data = null ";
         $virgula = ",";
         if(trim($this->tf15_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf15_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf15_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf15_observacao"])){ 
       $sql  .= $virgula." tf15_observacao = '$this->tf15_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tf15_i_codigo!=null){
       $sql .= " tf15_i_codigo = $this->tf15_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf15_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16388,'$this->tf15_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_codigo"]) || $this->tf15_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2871,16388,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_i_codigo'))."','$this->tf15_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_cgsund"]) || $this->tf15_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,2871,16390,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_i_cgsund'))."','$this->tf15_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacusto"]) || $this->tf15_i_ajudacusto != "")
           $resac = db_query("insert into db_acount values($acount,2871,16389,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_i_ajudacusto'))."','$this->tf15_i_ajudacusto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_i_ajudacustopedido"]) || $this->tf15_i_ajudacustopedido != "")
           $resac = db_query("insert into db_acount values($acount,2871,16391,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_i_ajudacustopedido'))."','$this->tf15_i_ajudacustopedido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_f_valoremitido"]) || $this->tf15_f_valoremitido != "")
           $resac = db_query("insert into db_acount values($acount,2871,16392,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_f_valoremitido'))."','$this->tf15_f_valoremitido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_d_data"]) || $this->tf15_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2871,16393,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_d_data'))."','$this->tf15_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf15_observacao"]) || $this->tf15_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2871,18275,'".AddSlashes(pg_result($resaco,$conresaco,'tf15_observacao'))."','$this->tf15_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_beneficiadosajudacusto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_beneficiadosajudacusto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf15_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf15_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16388,'$tf15_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2871,16388,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,16390,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,16389,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_i_ajudacusto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,16391,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_i_ajudacustopedido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,16392,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_f_valoremitido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,16393,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2871,18275,'','".AddSlashes(pg_result($resaco,$iresaco,'tf15_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_beneficiadosajudacusto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf15_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf15_i_codigo = $tf15_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_beneficiadosajudacusto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_beneficiadosajudacusto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf15_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_beneficiadosajudacusto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_beneficiadosajudacusto ";
     $sql .= "      inner join tfd_ajudacusto  on  tfd_ajudacusto.tf12_i_codigo = tfd_beneficiadosajudacusto.tf15_i_ajudacusto";
     $sql .= "      inner join tfd_ajudacustopedido  on  tfd_ajudacustopedido.tf14_i_codigo = tfd_beneficiadosajudacusto.tf15_i_ajudacustopedido";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_beneficiadosajudacusto.tf15_i_cgsund";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = tfd_ajudacusto.tf12_i_procedimento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_ajudacustopedido.tf14_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_ajudacustopedido.tf14_i_pedidotfd";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = tfd_ajudacustopedido.tf14_i_cgsretirou";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf15_i_codigo!=null ){
         $sql2 .= " where tfd_beneficiadosajudacusto.tf15_i_codigo = $tf15_i_codigo "; 
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
   function sql_query_file ( $tf15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_beneficiadosajudacusto ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf15_i_codigo!=null ){
         $sql2 .= " where tfd_beneficiadosajudacusto.tf15_i_codigo = $tf15_i_codigo "; 
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
   // funcao do sql  com a retirada de algumas ligações para a busca ficar mais rápida
   function sql_query2 ( $tf15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_beneficiadosajudacusto ";
     $sql .= "      inner join tfd_ajudacusto  on  tfd_ajudacusto.tf12_i_codigo = tfd_beneficiadosajudacusto.tf15_i_ajudacusto";
     $sql .= "      inner join tfd_ajudacustopedido  on  tfd_ajudacustopedido.tf14_i_codigo = tfd_beneficiadosajudacusto.tf15_i_ajudacustopedido";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_beneficiadosajudacusto.tf15_i_cgsund";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = tfd_ajudacusto.tf12_i_procedimento";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_ajudacustopedido.tf14_i_pedidotfd";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_ajudacustopedido.tf14_i_login";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = tfd_ajudacustopedido.tf14_i_cgsretirou";
     $sql2 = "";
     if($dbwhere==""){
       if($tf15_i_codigo!=null ){
         $sql2 .= " where tfd_beneficiadosajudacusto.tf15_i_codigo = $tf15_i_codigo "; 
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