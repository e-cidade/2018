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

//MODULO: transito
//CLASSE DA ENTIDADE veiculos_env
class cl_veiculos_env { 
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
   var $tr08_id = 0; 
   var $tr08_idveiculo = 0; 
   var $tr08_municipio = 0; 
   var $tr08_placa = null; 
   var $tr08_idacidente = 0; 
   var $tr08_condnome = null; 
   var $tr08_idhabilitacao = 0; 
   var $tr08_sexo = null; 
   var $tr08_idade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tr08_id = int8 = Código do veiculo 
                 tr08_idveiculo = int4 = Tipo do veiculo 
                 tr08_municipio = int8 = Municipio do veiculo 
                 tr08_placa = varchar(7) = Placa do veiculo 
                 tr08_idacidente = int8 = codigo do acidente 
                 tr08_condnome = varchar(50) = Condutor 
                 tr08_idhabilitacao = int8 = Tipo de Habilitação 
                 tr08_sexo = varchar(15) = Sexo 
                 tr08_idade = int4 = Idade 
                 ";
   //funcao construtor da classe 
   function cl_veiculos_env() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veiculos_env"); 
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
       $this->tr08_id = ($this->tr08_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_id"]:$this->tr08_id);
       $this->tr08_idveiculo = ($this->tr08_idveiculo == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_idveiculo"]:$this->tr08_idveiculo);
       $this->tr08_municipio = ($this->tr08_municipio == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_municipio"]:$this->tr08_municipio);
       $this->tr08_placa = ($this->tr08_placa == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_placa"]:$this->tr08_placa);
       $this->tr08_idacidente = ($this->tr08_idacidente == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_idacidente"]:$this->tr08_idacidente);
       $this->tr08_condnome = ($this->tr08_condnome == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_condnome"]:$this->tr08_condnome);
       $this->tr08_idhabilitacao = ($this->tr08_idhabilitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_idhabilitacao"]:$this->tr08_idhabilitacao);
       $this->tr08_sexo = ($this->tr08_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_sexo"]:$this->tr08_sexo);
       $this->tr08_idade = ($this->tr08_idade == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_idade"]:$this->tr08_idade);
     }else{
       $this->tr08_id = ($this->tr08_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr08_id"]:$this->tr08_id);
     }
   }
   // funcao para inclusao
   function incluir ($tr08_id){ 
      $this->atualizacampos();
     if($this->tr08_idveiculo == null ){ 
       $this->erro_sql = " Campo Tipo do veiculo nao Informado.";
       $this->erro_campo = "tr08_idveiculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr08_municipio == null ){ 
       $this->erro_sql = " Campo Municipio do veiculo nao Informado.";
       $this->erro_campo = "tr08_municipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr08_idacidente == null ){ 
       $this->erro_sql = " Campo codigo do acidente nao Informado.";
       $this->erro_campo = "tr08_idacidente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr08_idhabilitacao == null ){ 
       $this->tr08_idhabilitacao = "0";
     }
     if($this->tr08_sexo == null ){ 
       $this->erro_sql = " Campo Sexo nao Informado.";
       $this->erro_campo = "tr08_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr08_idade == null ){ 
       $this->tr08_idade = "0";
     }
     if($tr08_id == "" || $tr08_id == null ){
       $result = db_query("select nextval('veiculos_env_tr08_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veiculos_env_tr08_id_seq do campo: tr08_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tr08_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veiculos_env_tr08_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $tr08_id)){
         $this->erro_sql = " Campo tr08_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tr08_id = $tr08_id; 
       }
     }
     if(($this->tr08_id == null) || ($this->tr08_id == "") ){ 
       $this->erro_sql = " Campo tr08_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veiculos_env(
                                       tr08_id 
                                      ,tr08_idveiculo 
                                      ,tr08_municipio 
                                      ,tr08_placa 
                                      ,tr08_idacidente 
                                      ,tr08_condnome 
                                      ,tr08_idhabilitacao 
                                      ,tr08_sexo 
                                      ,tr08_idade 
                       )
                values (
                                $this->tr08_id 
                               ,$this->tr08_idveiculo 
                               ,$this->tr08_municipio 
                               ,'$this->tr08_placa' 
                               ,$this->tr08_idacidente 
                               ,'$this->tr08_condnome' 
                               ,$this->tr08_idhabilitacao 
                               ,'$this->tr08_sexo' 
                               ,$this->tr08_idade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Veiculos envolvidos no acidente ($this->tr08_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Veiculos envolvidos no acidente já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Veiculos envolvidos no acidente ($this->tr08_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr08_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tr08_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5633,'$this->tr08_id','I')");
       $resac = db_query("insert into db_acount values($acount,875,5633,'','".AddSlashes(pg_result($resaco,0,'tr08_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5637,'','".AddSlashes(pg_result($resaco,0,'tr08_idveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5638,'','".AddSlashes(pg_result($resaco,0,'tr08_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5639,'','".AddSlashes(pg_result($resaco,0,'tr08_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5634,'','".AddSlashes(pg_result($resaco,0,'tr08_idacidente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5631,'','".AddSlashes(pg_result($resaco,0,'tr08_condnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5636,'','".AddSlashes(pg_result($resaco,0,'tr08_idhabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5616,'','".AddSlashes(pg_result($resaco,0,'tr08_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,875,5635,'','".AddSlashes(pg_result($resaco,0,'tr08_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tr08_id=null) { 
      $this->atualizacampos();
     $sql = " update veiculos_env set ";
     $virgula = "";
     if(trim($this->tr08_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_id"])){ 
       $sql  .= $virgula." tr08_id = $this->tr08_id ";
       $virgula = ",";
       if(trim($this->tr08_id) == null ){ 
         $this->erro_sql = " Campo Código do veiculo nao Informado.";
         $this->erro_campo = "tr08_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr08_idveiculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_idveiculo"])){ 
       $sql  .= $virgula." tr08_idveiculo = $this->tr08_idveiculo ";
       $virgula = ",";
       if(trim($this->tr08_idveiculo) == null ){ 
         $this->erro_sql = " Campo Tipo do veiculo nao Informado.";
         $this->erro_campo = "tr08_idveiculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr08_municipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_municipio"])){ 
       $sql  .= $virgula." tr08_municipio = $this->tr08_municipio ";
       $virgula = ",";
       if(trim($this->tr08_municipio) == null ){ 
         $this->erro_sql = " Campo Municipio do veiculo nao Informado.";
         $this->erro_campo = "tr08_municipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr08_placa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_placa"])){ 
       $sql  .= $virgula." tr08_placa = '$this->tr08_placa' ";
       $virgula = ",";
     }
     if(trim($this->tr08_idacidente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_idacidente"])){ 
       $sql  .= $virgula." tr08_idacidente = $this->tr08_idacidente ";
       $virgula = ",";
       if(trim($this->tr08_idacidente) == null ){ 
         $this->erro_sql = " Campo codigo do acidente nao Informado.";
         $this->erro_campo = "tr08_idacidente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr08_condnome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_condnome"])){ 
       $sql  .= $virgula." tr08_condnome = '$this->tr08_condnome' ";
       $virgula = ",";
     }
     if(trim($this->tr08_idhabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_idhabilitacao"])){ 
        if(trim($this->tr08_idhabilitacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tr08_idhabilitacao"])){ 
           $this->tr08_idhabilitacao = "0" ; 
        } 
       $sql  .= $virgula." tr08_idhabilitacao = $this->tr08_idhabilitacao ";
       $virgula = ",";
     }
     if(trim($this->tr08_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_sexo"])){ 
       $sql  .= $virgula." tr08_sexo = '$this->tr08_sexo' ";
       $virgula = ",";
       if(trim($this->tr08_sexo) == null ){ 
         $this->erro_sql = " Campo Sexo nao Informado.";
         $this->erro_campo = "tr08_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr08_idade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr08_idade"])){ 
        if(trim($this->tr08_idade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tr08_idade"])){ 
           $this->tr08_idade = "0" ; 
        } 
       $sql  .= $virgula." tr08_idade = $this->tr08_idade ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tr08_id!=null){
       $sql .= " tr08_id = $this->tr08_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tr08_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5633,'$this->tr08_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_id"]))
           $resac = db_query("insert into db_acount values($acount,875,5633,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_id'))."','$this->tr08_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_idveiculo"]))
           $resac = db_query("insert into db_acount values($acount,875,5637,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_idveiculo'))."','$this->tr08_idveiculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_municipio"]))
           $resac = db_query("insert into db_acount values($acount,875,5638,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_municipio'))."','$this->tr08_municipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_placa"]))
           $resac = db_query("insert into db_acount values($acount,875,5639,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_placa'))."','$this->tr08_placa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_idacidente"]))
           $resac = db_query("insert into db_acount values($acount,875,5634,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_idacidente'))."','$this->tr08_idacidente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_condnome"]))
           $resac = db_query("insert into db_acount values($acount,875,5631,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_condnome'))."','$this->tr08_condnome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_idhabilitacao"]))
           $resac = db_query("insert into db_acount values($acount,875,5636,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_idhabilitacao'))."','$this->tr08_idhabilitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_sexo"]))
           $resac = db_query("insert into db_acount values($acount,875,5616,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_sexo'))."','$this->tr08_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr08_idade"]))
           $resac = db_query("insert into db_acount values($acount,875,5635,'".AddSlashes(pg_result($resaco,$conresaco,'tr08_idade'))."','$this->tr08_idade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Veiculos envolvidos no acidente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr08_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Veiculos envolvidos no acidente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr08_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr08_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tr08_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tr08_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5633,'$tr08_id','E')");
         $resac = db_query("insert into db_acount values($acount,875,5633,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5637,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_idveiculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5638,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5639,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_placa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5634,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_idacidente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5631,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_condnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5636,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_idhabilitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5616,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,875,5635,'','".AddSlashes(pg_result($resaco,$iresaco,'tr08_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veiculos_env
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tr08_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr08_id = $tr08_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Veiculos envolvidos no acidente nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tr08_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Veiculos envolvidos no acidente nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tr08_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tr08_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:veiculos_env";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $tr08_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veiculos_env ";
     $sql .= "      inner join db_cepmunic  on  db_cepmunic.db10_codigo = veiculos_env.tr08_municipio";
     $sql .= "      inner join tipo_veiculos  on  tipo_veiculos.tr05_id = veiculos_env.tr08_idveiculo";
     $sql .= "      inner join acidentes  on  acidentes.tr07_id = veiculos_env.tr08_idacidente";
     $sql .= "      inner join tipo_habilitacao  on  tipo_habilitacao.tr09_id = veiculos_env.tr08_idhabilitacao";
     $sql .= "      inner join db_uf  on  db_uf.db12_codigo = db_cepmunic.db10_uf";
     $sql .= "      inner join bairro  on  bairro.j13_codi = acidentes.tr07_idbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = acidentes.tr07_local1";
     $sql .= "      inner join causas  on  causas.tr02_id = acidentes.tr07_idcausa";
     $sql .= "      inner join tipo_acidentes  on  tipo_acidentes.tr01_id = acidentes.tr07_tipoacid";
     $sql .= "      inner join tipo_pista  on  tipo_pista.tr03_id = acidentes.tr07_idpista";
     $sql .= "      inner join tipo_tempo  on  tipo_tempo.tr04_id = acidentes.tr07_idtempo";
     $sql2 = "";
     if($dbwhere==""){
       if($tr08_id!=null ){
         $sql2 .= " where veiculos_env.tr08_id = $tr08_id "; 
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
   function sql_query_file ( $tr08_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veiculos_env ";
     $sql2 = "";
     if($dbwhere==""){
       if($tr08_id!=null ){
         $sql2 .= " where veiculos_env.tr08_id = $tr08_id "; 
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