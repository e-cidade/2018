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
//CLASSE DA ENTIDADE vitimas_acid
class cl_vitimas_acid { 
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
   var $tr10_id = 0; 
   var $tr10_idacidente = 0; 
   var $tr10_idvitima = 0; 
   var $tr10_nome = null; 
   var $tr10_sexo = 0; 
   var $tr10_idade = 0; 
   var $tr10_situacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tr10_id = int8 = Código da vitima 
                 tr10_idacidente = int8 = Código do acidente 
                 tr10_idvitima = int8 = Tipo de vitima 
                 tr10_nome = varchar(30) = Nome da Vitima 
                 tr10_sexo = int8 = Sexo 
                 tr10_idade = int4 = Idade 
                 tr10_situacao = char(1) = Situação da Vitima 
                 ";
   //funcao construtor da classe 
   function cl_vitimas_acid() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vitimas_acid"); 
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
       $this->tr10_id = ($this->tr10_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_id"]:$this->tr10_id);
       $this->tr10_idacidente = ($this->tr10_idacidente == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_idacidente"]:$this->tr10_idacidente);
       $this->tr10_idvitima = ($this->tr10_idvitima == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_idvitima"]:$this->tr10_idvitima);
       $this->tr10_nome = ($this->tr10_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_nome"]:$this->tr10_nome);
       $this->tr10_sexo = ($this->tr10_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_sexo"]:$this->tr10_sexo);
       $this->tr10_idade = ($this->tr10_idade == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_idade"]:$this->tr10_idade);
       $this->tr10_situacao = ($this->tr10_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_situacao"]:$this->tr10_situacao);
     }else{
       $this->tr10_id = ($this->tr10_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr10_id"]:$this->tr10_id);
     }
   }
   // funcao para inclusao
   function incluir ($tr10_id){ 
      $this->atualizacampos();
     if($this->tr10_idacidente == null ){ 
       $this->erro_sql = " Campo Código do acidente nao Informado.";
       $this->erro_campo = "tr10_idacidente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr10_idvitima == null ){ 
       $this->erro_sql = " Campo Tipo de vitima nao Informado.";
       $this->erro_campo = "tr10_idvitima";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr10_sexo == null ){ 
       $this->erro_sql = " Campo Sexo nao Informado.";
       $this->erro_campo = "tr10_sexo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr10_idade == null ){ 
       $this->tr10_idade = "0";
     }
     if($this->tr10_situacao == null ){ 
       $this->erro_sql = " Campo Situação da Vitima nao Informado.";
       $this->erro_campo = "tr10_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tr10_id == "" || $tr10_id == null ){
       $result = db_query("select nextval('vitimas_acid_tr10_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vitimas_acid_tr10_id_seq do campo: tr10_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tr10_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vitimas_acid_tr10_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $tr10_id)){
         $this->erro_sql = " Campo tr10_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tr10_id = $tr10_id; 
       }
     }
     if(($this->tr10_id == null) || ($this->tr10_id == "") ){ 
       $this->erro_sql = " Campo tr10_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vitimas_acid(
                                       tr10_id 
                                      ,tr10_idacidente 
                                      ,tr10_idvitima 
                                      ,tr10_nome 
                                      ,tr10_sexo 
                                      ,tr10_idade 
                                      ,tr10_situacao 
                       )
                values (
                                $this->tr10_id 
                               ,$this->tr10_idacidente 
                               ,$this->tr10_idvitima 
                               ,'$this->tr10_nome' 
                               ,$this->tr10_sexo 
                               ,$this->tr10_idade 
                               ,'$this->tr10_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "vitimas do acidentes ($this->tr10_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "vitimas do acidentes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "vitimas do acidentes ($this->tr10_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr10_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tr10_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5644,'$this->tr10_id','I')");
       $resac = db_query("insert into db_acount values($acount,877,5644,'','".AddSlashes(pg_result($resaco,0,'tr10_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5645,'','".AddSlashes(pg_result($resaco,0,'tr10_idacidente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5647,'','".AddSlashes(pg_result($resaco,0,'tr10_idvitima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5648,'','".AddSlashes(pg_result($resaco,0,'tr10_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5649,'','".AddSlashes(pg_result($resaco,0,'tr10_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5646,'','".AddSlashes(pg_result($resaco,0,'tr10_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,877,5650,'','".AddSlashes(pg_result($resaco,0,'tr10_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tr10_id=null) { 
      $this->atualizacampos();
     $sql = " update vitimas_acid set ";
     $virgula = "";
     if(trim($this->tr10_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_id"])){ 
       $sql  .= $virgula." tr10_id = $this->tr10_id ";
       $virgula = ",";
       if(trim($this->tr10_id) == null ){ 
         $this->erro_sql = " Campo Código da vitima nao Informado.";
         $this->erro_campo = "tr10_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr10_idacidente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_idacidente"])){ 
       $sql  .= $virgula." tr10_idacidente = $this->tr10_idacidente ";
       $virgula = ",";
       if(trim($this->tr10_idacidente) == null ){ 
         $this->erro_sql = " Campo Código do acidente nao Informado.";
         $this->erro_campo = "tr10_idacidente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr10_idvitima)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_idvitima"])){ 
       $sql  .= $virgula." tr10_idvitima = $this->tr10_idvitima ";
       $virgula = ",";
       if(trim($this->tr10_idvitima) == null ){ 
         $this->erro_sql = " Campo Tipo de vitima nao Informado.";
         $this->erro_campo = "tr10_idvitima";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr10_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_nome"])){ 
       $sql  .= $virgula." tr10_nome = '$this->tr10_nome' ";
       $virgula = ",";
     }
     if(trim($this->tr10_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_sexo"])){ 
       $sql  .= $virgula." tr10_sexo = $this->tr10_sexo ";
       $virgula = ",";
       if(trim($this->tr10_sexo) == null ){ 
         $this->erro_sql = " Campo Sexo nao Informado.";
         $this->erro_campo = "tr10_sexo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr10_idade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_idade"])){ 
        if(trim($this->tr10_idade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tr10_idade"])){ 
           $this->tr10_idade = "0" ; 
        } 
       $sql  .= $virgula." tr10_idade = $this->tr10_idade ";
       $virgula = ",";
     }
     if(trim($this->tr10_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr10_situacao"])){ 
       $sql  .= $virgula." tr10_situacao = '$this->tr10_situacao' ";
       $virgula = ",";
       if(trim($this->tr10_situacao) == null ){ 
         $this->erro_sql = " Campo Situação da Vitima nao Informado.";
         $this->erro_campo = "tr10_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tr10_id!=null){
       $sql .= " tr10_id = $this->tr10_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tr10_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5644,'$this->tr10_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_id"]))
           $resac = db_query("insert into db_acount values($acount,877,5644,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_id'))."','$this->tr10_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_idacidente"]))
           $resac = db_query("insert into db_acount values($acount,877,5645,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_idacidente'))."','$this->tr10_idacidente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_idvitima"]))
           $resac = db_query("insert into db_acount values($acount,877,5647,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_idvitima'))."','$this->tr10_idvitima',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_nome"]))
           $resac = db_query("insert into db_acount values($acount,877,5648,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_nome'))."','$this->tr10_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_sexo"]))
           $resac = db_query("insert into db_acount values($acount,877,5649,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_sexo'))."','$this->tr10_sexo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_idade"]))
           $resac = db_query("insert into db_acount values($acount,877,5646,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_idade'))."','$this->tr10_idade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr10_situacao"]))
           $resac = db_query("insert into db_acount values($acount,877,5650,'".AddSlashes(pg_result($resaco,$conresaco,'tr10_situacao'))."','$this->tr10_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vitimas do acidentes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr10_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vitimas do acidentes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr10_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr10_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tr10_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tr10_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5644,'$tr10_id','E')");
         $resac = db_query("insert into db_acount values($acount,877,5644,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5645,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_idacidente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5647,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_idvitima'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5648,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5649,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_sexo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5646,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_idade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,877,5650,'','".AddSlashes(pg_result($resaco,$iresaco,'tr10_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vitimas_acid
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tr10_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr10_id = $tr10_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "vitimas do acidentes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tr10_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "vitimas do acidentes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tr10_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tr10_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:vitimas_acid";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $tr10_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vitimas_acid ";
     $sql .= "      inner join tipo_vitimas  on  tipo_vitimas.tr06_id = vitimas_acid.tr10_idvitima";
     $sql .= "      inner join acidentes  on  acidentes.tr07_id = vitimas_acid.tr10_idacidente";
     $sql .= "      inner join bairro  on  bairro.j13_codi = acidentes.tr07_idbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = acidentes.tr07_local1";
     $sql .= "      inner join causas  on  causas.tr02_id = acidentes.tr07_idcausa";
     $sql .= "      inner join tipo_acidentes  on  tipo_acidentes.tr01_id = acidentes.tr07_tipoacid";
     $sql .= "      inner join tipo_pista  on  tipo_pista.tr03_id = acidentes.tr07_idpista";
     $sql .= "      inner join tipo_tempo  on  tipo_tempo.tr04_id = acidentes.tr07_idtempo";
     $sql2 = "";
     if($dbwhere==""){
       if($tr10_id!=null ){
         $sql2 .= " where vitimas_acid.tr10_id = $tr10_id "; 
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
   function sql_query_file ( $tr10_id=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vitimas_acid ";
     $sql2 = "";
     if($dbwhere==""){
       if($tr10_id!=null ){
         $sql2 .= " where vitimas_acid.tr10_id = $tr10_id "; 
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