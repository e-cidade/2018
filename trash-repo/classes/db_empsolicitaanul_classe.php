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

//MODULO: empenho
//CLASSE DA ENTIDADE empsolicitaanul
class cl_empsolicitaanul { 
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
   var $e35_sequencial = 0; 
   var $e35_numemp = 0; 
   var $e35_usuario = 0; 
   var $e35_hora = null; 
   var $e35_data_dia = null; 
   var $e35_data_mes = null; 
   var $e35_data_ano = null; 
   var $e35_data = null; 
   var $e35_tipo = 0; 
   var $e35_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e35_sequencial = int4 = Código Sequencial 
                 e35_numemp = int4 = Código do Empenho 
                 e35_usuario = int4 = código do Usuário 
                 e35_hora = char(5) = Hora 
                 e35_data = date = Data 
                 e35_tipo = int4 = TIpo 
                 e35_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_empsolicitaanul() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empsolicitaanul"); 
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
       $this->e35_sequencial = ($this->e35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_sequencial"]:$this->e35_sequencial);
       $this->e35_numemp = ($this->e35_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_numemp"]:$this->e35_numemp);
       $this->e35_usuario = ($this->e35_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_usuario"]:$this->e35_usuario);
       $this->e35_hora = ($this->e35_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_hora"]:$this->e35_hora);
       if($this->e35_data == ""){
         $this->e35_data_dia = ($this->e35_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_data_dia"]:$this->e35_data_dia);
         $this->e35_data_mes = ($this->e35_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_data_mes"]:$this->e35_data_mes);
         $this->e35_data_ano = ($this->e35_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_data_ano"]:$this->e35_data_ano);
         if($this->e35_data_dia != ""){
            $this->e35_data = $this->e35_data_ano."-".$this->e35_data_mes."-".$this->e35_data_dia;
         }
       }
       $this->e35_tipo = ($this->e35_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_tipo"]:$this->e35_tipo);
       $this->e35_situacao = ($this->e35_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_situacao"]:$this->e35_situacao);
     }else{
       $this->e35_sequencial = ($this->e35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e35_sequencial"]:$this->e35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e35_sequencial){ 
      $this->atualizacampos();
     if($this->e35_numemp == null ){ 
       $this->erro_sql = " Campo Código do Empenho nao Informado.";
       $this->erro_campo = "e35_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e35_usuario == null ){ 
       $this->erro_sql = " Campo código do Usuário nao Informado.";
       $this->erro_campo = "e35_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e35_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e35_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e35_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "e35_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e35_tipo == null ){ 
       $this->erro_sql = " Campo TIpo nao Informado.";
       $this->erro_campo = "e35_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e35_situacao == null ){ 
       $this->e35_situacao = "1";
     }
     if($e35_sequencial == "" || $e35_sequencial == null ){
       $result = db_query("select nextval('empsolicitaanul_e35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empsolicitaanul_e35_sequencial_seq do campo: e35_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empsolicitaanul_e35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e35_sequencial)){
         $this->erro_sql = " Campo e35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e35_sequencial = $e35_sequencial; 
       }
     }
     if(($this->e35_sequencial == null) || ($this->e35_sequencial == "") ){ 
       $this->erro_sql = " Campo e35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empsolicitaanul(
                                       e35_sequencial 
                                      ,e35_numemp 
                                      ,e35_usuario 
                                      ,e35_hora 
                                      ,e35_data 
                                      ,e35_tipo 
                                      ,e35_situacao 
                       )
                values (
                                $this->e35_sequencial 
                               ,$this->e35_numemp 
                               ,$this->e35_usuario 
                               ,'$this->e35_hora' 
                               ,".($this->e35_data == "null" || $this->e35_data == ""?"null":"'".$this->e35_data."'")." 
                               ,$this->e35_tipo 
                               ,$this->e35_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Solicitação de anulaçao de Empenho ($this->e35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Solicitação de anulaçao de Empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Solicitação de anulaçao de Empenho ($this->e35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e35_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10904,'$this->e35_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1882,10904,'','".AddSlashes(pg_result($resaco,0,'e35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10905,'','".AddSlashes(pg_result($resaco,0,'e35_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10906,'','".AddSlashes(pg_result($resaco,0,'e35_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10907,'','".AddSlashes(pg_result($resaco,0,'e35_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10908,'','".AddSlashes(pg_result($resaco,0,'e35_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10909,'','".AddSlashes(pg_result($resaco,0,'e35_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1882,10927,'','".AddSlashes(pg_result($resaco,0,'e35_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empsolicitaanul set ";
     $virgula = "";
     if(trim($this->e35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_sequencial"])){ 
       $sql  .= $virgula." e35_sequencial = $this->e35_sequencial ";
       $virgula = ",";
       if(trim($this->e35_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e35_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_numemp"])){ 
       $sql  .= $virgula." e35_numemp = $this->e35_numemp ";
       $virgula = ",";
       if(trim($this->e35_numemp) == null ){ 
         $this->erro_sql = " Campo Código do Empenho nao Informado.";
         $this->erro_campo = "e35_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e35_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_usuario"])){ 
       $sql  .= $virgula." e35_usuario = $this->e35_usuario ";
       $virgula = ",";
       if(trim($this->e35_usuario) == null ){ 
         $this->erro_sql = " Campo código do Usuário nao Informado.";
         $this->erro_campo = "e35_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e35_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_hora"])){ 
       $sql  .= $virgula." e35_hora = '$this->e35_hora' ";
       $virgula = ",";
       if(trim($this->e35_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e35_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e35_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e35_data_dia"] !="") ){ 
       $sql  .= $virgula." e35_data = '$this->e35_data' ";
       $virgula = ",";
       if(trim($this->e35_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "e35_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e35_data_dia"])){ 
         $sql  .= $virgula." e35_data = null ";
         $virgula = ",";
         if(trim($this->e35_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "e35_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e35_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_tipo"])){ 
       $sql  .= $virgula." e35_tipo = $this->e35_tipo ";
       $virgula = ",";
       if(trim($this->e35_tipo) == null ){ 
         $this->erro_sql = " Campo TIpo nao Informado.";
         $this->erro_campo = "e35_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e35_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e35_situacao"])){ 
        if(trim($this->e35_situacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e35_situacao"])){ 
           $this->e35_situacao = "0" ; 
        } 
       $sql  .= $virgula." e35_situacao = $this->e35_situacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e35_sequencial!=null){
       $sql .= " e35_sequencial = $this->e35_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e35_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10904,'$this->e35_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1882,10904,'".AddSlashes(pg_result($resaco,$conresaco,'e35_sequencial'))."','$this->e35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_numemp"]))
           $resac = db_query("insert into db_acount values($acount,1882,10905,'".AddSlashes(pg_result($resaco,$conresaco,'e35_numemp'))."','$this->e35_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1882,10906,'".AddSlashes(pg_result($resaco,$conresaco,'e35_usuario'))."','$this->e35_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_hora"]))
           $resac = db_query("insert into db_acount values($acount,1882,10907,'".AddSlashes(pg_result($resaco,$conresaco,'e35_hora'))."','$this->e35_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_data"]))
           $resac = db_query("insert into db_acount values($acount,1882,10908,'".AddSlashes(pg_result($resaco,$conresaco,'e35_data'))."','$this->e35_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1882,10909,'".AddSlashes(pg_result($resaco,$conresaco,'e35_tipo'))."','$this->e35_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e35_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1882,10927,'".AddSlashes(pg_result($resaco,$conresaco,'e35_situacao'))."','$this->e35_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação de anulaçao de Empenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação de anulaçao de Empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e35_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e35_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10904,'$e35_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1882,10904,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10905,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10906,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10907,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10908,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10909,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1882,10927,'','".AddSlashes(pg_result($resaco,$iresaco,'e35_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empsolicitaanul
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e35_sequencial = $e35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Solicitação de anulaçao de Empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Solicitação de anulaçao de Empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empsolicitaanul";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empsolicitaanul ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empsolicitaanul.e35_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empsolicitaanul.e35_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($e35_sequencial!=null ){
         $sql2 .= " where empsolicitaanul.e35_sequencial = $e35_sequencial "; 
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
   function sql_query_file ( $e35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empsolicitaanul ";
     $sql2 = "";
     if($dbwhere==""){
       if($e35_sequencial!=null ){
         $sql2 .= " where empsolicitaanul.e35_sequencial = $e35_sequencial "; 
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