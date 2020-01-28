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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE cgs_cartaosus
class cl_cgs_cartaosus { 
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
   var $s115_i_codigo = 0; 
   var $s115_i_cgs = 0; 
   var $s115_c_cartaosus = null; 
   var $s115_c_tipo = null; 
   var $s115_i_entrada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s115_i_codigo = int4 = C�digo 
                 s115_i_cgs = int4 = CGS 
                 s115_c_cartaosus = char(15) = Cart�o SUS 
                 s115_c_tipo = char(1) = Tipo 
                 s115_i_entrada = int4 = Entrada 
                 ";
   //funcao construtor da classe 
   function cl_cgs_cartaosus() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgs_cartaosus"); 
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
       $this->s115_i_codigo = ($this->s115_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_i_codigo"]:$this->s115_i_codigo);
       $this->s115_i_cgs = ($this->s115_i_cgs == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_i_cgs"]:$this->s115_i_cgs);
       $this->s115_c_cartaosus = ($this->s115_c_cartaosus == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_c_cartaosus"]:$this->s115_c_cartaosus);
       $this->s115_c_tipo = ($this->s115_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_c_tipo"]:$this->s115_c_tipo);
       $this->s115_i_entrada = ($this->s115_i_entrada == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_i_entrada"]:$this->s115_i_entrada);
     }else{
       $this->s115_i_codigo = ($this->s115_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s115_i_codigo"]:$this->s115_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s115_i_codigo){ 
      $this->atualizacampos();
     if($this->s115_i_cgs == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "s115_i_cgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s115_c_cartaosus == null ){ 
       $this->erro_sql = " Campo Cart�o SUS nao Informado.";
       $this->erro_campo = "s115_c_cartaosus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s115_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "s115_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s115_i_entrada == null ){ 
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "s115_i_entrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s115_i_codigo == "" || $s115_i_codigo == null ){
       $result = db_query("select nextval('cgs_cartaosus_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgs_cartaosus_codigo_seq do campo: s115_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s115_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgs_cartaosus_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s115_i_codigo)){
         $this->erro_sql = " Campo s115_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s115_i_codigo = $s115_i_codigo; 
       }
     }
     if(($this->s115_i_codigo == null) || ($this->s115_i_codigo == "") ){ 
       $this->erro_sql = " Campo s115_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgs_cartaosus(
                                       s115_i_codigo 
                                      ,s115_i_cgs 
                                      ,s115_c_cartaosus 
                                      ,s115_c_tipo 
                                      ,s115_i_entrada 
                       )
                values (
                                $this->s115_i_codigo 
                               ,$this->s115_i_cgs 
                               ,'$this->s115_c_cartaosus' 
                               ,'$this->s115_c_tipo' 
                               ,$this->s115_i_entrada 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cart�o SUS ($this->s115_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cart�o SUS j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cart�o SUS ($this->s115_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s115_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s115_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13998,'$this->s115_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2460,13998,'','".AddSlashes(pg_result($resaco,0,'s115_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2460,13999,'','".AddSlashes(pg_result($resaco,0,'s115_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2460,14000,'','".AddSlashes(pg_result($resaco,0,'s115_c_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2460,14001,'','".AddSlashes(pg_result($resaco,0,'s115_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2460,15304,'','".AddSlashes(pg_result($resaco,0,'s115_i_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s115_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cgs_cartaosus set ";
     $virgula = "";
     if(trim($this->s115_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s115_i_codigo"])){ 
       $sql  .= $virgula." s115_i_codigo = $this->s115_i_codigo ";
       $virgula = ",";
       if(trim($this->s115_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "s115_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s115_i_cgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s115_i_cgs"])){ 
       $sql  .= $virgula." s115_i_cgs = $this->s115_i_cgs ";
       $virgula = ",";
       if(trim($this->s115_i_cgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "s115_i_cgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s115_c_cartaosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s115_c_cartaosus"])){ 
       $sql  .= $virgula." s115_c_cartaosus = '$this->s115_c_cartaosus' ";
       $virgula = ",";
       if(trim($this->s115_c_cartaosus) == null ){ 
         $this->erro_sql = " Campo Cart�o SUS nao Informado.";
         $this->erro_campo = "s115_c_cartaosus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s115_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s115_c_tipo"])){ 
       $sql  .= $virgula." s115_c_tipo = '$this->s115_c_tipo' ";
       $virgula = ",";
       if(trim($this->s115_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "s115_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s115_i_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s115_i_entrada"])){ 
       $sql  .= $virgula." s115_i_entrada = $this->s115_i_entrada ";
       $virgula = ",";
       if(trim($this->s115_i_entrada) == null ){ 
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "s115_i_entrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s115_i_codigo!=null){
       $sql .= " s115_i_codigo = $this->s115_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s115_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13998,'$this->s115_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s115_i_codigo"]) || $this->s115_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2460,13998,'".AddSlashes(pg_result($resaco,$conresaco,'s115_i_codigo'))."','$this->s115_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s115_i_cgs"]) || $this->s115_i_cgs != "")
           $resac = db_query("insert into db_acount values($acount,2460,13999,'".AddSlashes(pg_result($resaco,$conresaco,'s115_i_cgs'))."','$this->s115_i_cgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s115_c_cartaosus"]) || $this->s115_c_cartaosus != "")
           $resac = db_query("insert into db_acount values($acount,2460,14000,'".AddSlashes(pg_result($resaco,$conresaco,'s115_c_cartaosus'))."','$this->s115_c_cartaosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s115_c_tipo"]) || $this->s115_c_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2460,14001,'".AddSlashes(pg_result($resaco,$conresaco,'s115_c_tipo'))."','$this->s115_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s115_i_entrada"]) || $this->s115_i_entrada != "")
           $resac = db_query("insert into db_acount values($acount,2460,15304,'".AddSlashes(pg_result($resaco,$conresaco,'s115_i_entrada'))."','$this->s115_i_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cart�o SUS nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s115_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cart�o SUS nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s115_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s115_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s115_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s115_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13998,'$s115_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2460,13998,'','".AddSlashes(pg_result($resaco,$iresaco,'s115_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2460,13999,'','".AddSlashes(pg_result($resaco,$iresaco,'s115_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2460,14000,'','".AddSlashes(pg_result($resaco,$iresaco,'s115_c_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2460,14001,'','".AddSlashes(pg_result($resaco,$iresaco,'s115_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2460,15304,'','".AddSlashes(pg_result($resaco,$iresaco,'s115_i_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgs_cartaosus
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s115_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s115_i_codigo = $s115_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cart�o SUS nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s115_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cart�o SUS nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s115_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s115_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgs_cartaosus";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s115_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgs_cartaosus ";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_cartaosus.s115_i_cgs";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s115_i_codigo!=null ){
         $sql2 .= " where cgs_cartaosus.s115_i_codigo = $s115_i_codigo "; 
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
   function sql_query_file ( $s115_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgs_cartaosus ";
     $sql2 = "";
     if($dbwhere==""){
       if($s115_i_codigo!=null ){
         $sql2 .= " where cgs_cartaosus.s115_i_codigo = $s115_i_codigo "; 
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