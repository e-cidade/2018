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

//MODULO: agua
//CLASSE DA ENTIDADE aguacorresp
class cl_aguacorresp { 
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
   var $x02_codcorresp = 0; 
   var $x02_codbairro = 0; 
   var $x02_codrua = 0; 
   var $x02_numero = 0; 
   var $x02_rota = 0; 
   var $x02_orientacao = null; 
   var $x02_complemento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x02_codcorresp = int4 = Código 
                 x02_codbairro = int4 = Bairro 
                 x02_codrua = int4 = Logradouro 
                 x02_numero = int4 = Número do imóvel 
                 x02_rota = int4 = Rota 
                 x02_orientacao = varchar(10) = orientação 
                 x02_complemento = varchar(20) = Complemento 
                 ";
   //funcao construtor da classe 
   function cl_aguacorresp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacorresp"); 
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
       $this->x02_codcorresp = ($this->x02_codcorresp == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_codcorresp"]:$this->x02_codcorresp);
       $this->x02_codbairro = ($this->x02_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_codbairro"]:$this->x02_codbairro);
       $this->x02_codrua = ($this->x02_codrua == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_codrua"]:$this->x02_codrua);
       $this->x02_numero = ($this->x02_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_numero"]:$this->x02_numero);
       $this->x02_rota = ($this->x02_rota == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_rota"]:$this->x02_rota);
       $this->x02_orientacao = ($this->x02_orientacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_orientacao"]:$this->x02_orientacao);
       $this->x02_complemento = ($this->x02_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_complemento"]:$this->x02_complemento);
     }else{
       $this->x02_codcorresp = ($this->x02_codcorresp == ""?@$GLOBALS["HTTP_POST_VARS"]["x02_codcorresp"]:$this->x02_codcorresp);
     }
   }
   // funcao para inclusao
   function incluir ($x02_codcorresp){ 
      $this->atualizacampos();
     if($this->x02_codbairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "x02_codbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x02_codrua == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "x02_codrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x02_numero == null ){ 
       $this->erro_sql = " Campo Número do imóvel nao Informado.";
       $this->erro_campo = "x02_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x02_rota == null ){ 
       $this->erro_sql = " Campo Rota nao Informado.";
       $this->erro_campo = "x02_rota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x02_orientacao == null ){ 
       $this->erro_sql = " Campo orientação nao Informado.";
       $this->erro_campo = "x02_orientacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x02_complemento == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "x02_complemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x02_codcorresp == "" || $x02_codcorresp == null ){
       $result = db_query("select nextval('aguacorresp_x02_codcorresp_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguacorresp_x02_codcorresp_seq do campo: x02_codcorresp"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x02_codcorresp = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguacorresp_x02_codcorresp_seq");
       if(($result != false) && (pg_result($result,0,0) < $x02_codcorresp)){
         $this->erro_sql = " Campo x02_codcorresp maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x02_codcorresp = $x02_codcorresp; 
       }
     }
     if(($this->x02_codcorresp == null) || ($this->x02_codcorresp == "") ){ 
       $this->erro_sql = " Campo x02_codcorresp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacorresp(
                                       x02_codcorresp 
                                      ,x02_codbairro 
                                      ,x02_codrua 
                                      ,x02_numero 
                                      ,x02_rota 
                                      ,x02_orientacao 
                                      ,x02_complemento 
                       )
                values (
                                $this->x02_codcorresp 
                               ,$this->x02_codbairro 
                               ,$this->x02_codrua 
                               ,$this->x02_numero 
                               ,$this->x02_rota 
                               ,'$this->x02_orientacao' 
                               ,'$this->x02_complemento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacorresp ($this->x02_codcorresp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacorresp já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacorresp ($this->x02_codcorresp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x02_codcorresp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x02_codcorresp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8399,'$this->x02_codcorresp','I')");
       $resac = db_query("insert into db_acount values($acount,1423,8399,'','".AddSlashes(pg_result($resaco,0,'x02_codcorresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8400,'','".AddSlashes(pg_result($resaco,0,'x02_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8401,'','".AddSlashes(pg_result($resaco,0,'x02_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8402,'','".AddSlashes(pg_result($resaco,0,'x02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8403,'','".AddSlashes(pg_result($resaco,0,'x02_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8404,'','".AddSlashes(pg_result($resaco,0,'x02_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1423,8519,'','".AddSlashes(pg_result($resaco,0,'x02_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x02_codcorresp=null) { 
      $this->atualizacampos();
     $sql = " update aguacorresp set ";
     $virgula = "";
     if(trim($this->x02_codcorresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_codcorresp"])){ 
       $sql  .= $virgula." x02_codcorresp = $this->x02_codcorresp ";
       $virgula = ",";
       if(trim($this->x02_codcorresp) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "x02_codcorresp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_codbairro"])){ 
       $sql  .= $virgula." x02_codbairro = $this->x02_codbairro ";
       $virgula = ",";
       if(trim($this->x02_codbairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "x02_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_codrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_codrua"])){ 
       $sql  .= $virgula." x02_codrua = $this->x02_codrua ";
       $virgula = ",";
       if(trim($this->x02_codrua) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "x02_codrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_numero"])){ 
       $sql  .= $virgula." x02_numero = $this->x02_numero ";
       $virgula = ",";
       if(trim($this->x02_numero) == null ){ 
         $this->erro_sql = " Campo Número do imóvel nao Informado.";
         $this->erro_campo = "x02_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_rota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_rota"])){ 
       $sql  .= $virgula." x02_rota = $this->x02_rota ";
       $virgula = ",";
       if(trim($this->x02_rota) == null ){ 
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "x02_rota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_orientacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_orientacao"])){ 
       $sql  .= $virgula." x02_orientacao = '$this->x02_orientacao' ";
       $virgula = ",";
       if(trim($this->x02_orientacao) == null ){ 
         $this->erro_sql = " Campo orientação nao Informado.";
         $this->erro_campo = "x02_orientacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x02_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x02_complemento"])){ 
       $sql  .= $virgula." x02_complemento = '$this->x02_complemento' ";
       $virgula = ",";
       if(trim($this->x02_complemento) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "x02_complemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x02_codcorresp!=null){
       $sql .= " x02_codcorresp = $this->x02_codcorresp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x02_codcorresp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8399,'$this->x02_codcorresp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_codcorresp"]))
           $resac = db_query("insert into db_acount values($acount,1423,8399,'".AddSlashes(pg_result($resaco,$conresaco,'x02_codcorresp'))."','$this->x02_codcorresp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_codbairro"]))
           $resac = db_query("insert into db_acount values($acount,1423,8400,'".AddSlashes(pg_result($resaco,$conresaco,'x02_codbairro'))."','$this->x02_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_codrua"]))
           $resac = db_query("insert into db_acount values($acount,1423,8401,'".AddSlashes(pg_result($resaco,$conresaco,'x02_codrua'))."','$this->x02_codrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_numero"]))
           $resac = db_query("insert into db_acount values($acount,1423,8402,'".AddSlashes(pg_result($resaco,$conresaco,'x02_numero'))."','$this->x02_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_rota"]))
           $resac = db_query("insert into db_acount values($acount,1423,8403,'".AddSlashes(pg_result($resaco,$conresaco,'x02_rota'))."','$this->x02_rota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_orientacao"]))
           $resac = db_query("insert into db_acount values($acount,1423,8404,'".AddSlashes(pg_result($resaco,$conresaco,'x02_orientacao'))."','$this->x02_orientacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x02_complemento"]))
           $resac = db_query("insert into db_acount values($acount,1423,8519,'".AddSlashes(pg_result($resaco,$conresaco,'x02_complemento'))."','$this->x02_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacorresp nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x02_codcorresp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacorresp nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x02_codcorresp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x02_codcorresp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x02_codcorresp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x02_codcorresp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8399,'$x02_codcorresp','E')");
         $resac = db_query("insert into db_acount values($acount,1423,8399,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_codcorresp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8400,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8401,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8402,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8403,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8404,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1423,8519,'','".AddSlashes(pg_result($resaco,$iresaco,'x02_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacorresp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x02_codcorresp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x02_codcorresp = $x02_codcorresp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacorresp nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x02_codcorresp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacorresp nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x02_codcorresp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x02_codcorresp;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacorresp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x02_codcorresp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacorresp ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguacorresp.x02_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguacorresp.x02_codrua";
     $sql2 = "";
     if($dbwhere==""){
       if($x02_codcorresp!=null ){
         $sql2 .= " where aguacorresp.x02_codcorresp = $x02_codcorresp "; 
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
   function sql_query_file ( $x02_codcorresp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacorresp ";
     $sql2 = "";
     if($dbwhere==""){
       if($x02_codcorresp!=null ){
         $sql2 .= " where aguacorresp.x02_codcorresp = $x02_codcorresp "; 
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