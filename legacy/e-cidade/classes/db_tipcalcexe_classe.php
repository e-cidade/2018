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

//MODULO: issqn
//CLASSE DA ENTIDADE tipcalcexe
class cl_tipcalcexe { 
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
   var $q83_codigo = 0; 
   var $q83_tipcalc = 0; 
   var $q83_anousu = 0; 
   var $q83_codven = 0; 
   var $q83_cadvencdescsimples = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q83_codigo = int4 = Codigo sequencial 
                 q83_tipcalc = int4 = codigo do tipo de calculo 
                 q83_anousu = int4 = Ano 
                 q83_codven = int4 = codigo do vencimento 
                 q83_cadvencdescsimples = int4 = Vencimento do simples 
                 ";
   //funcao construtor da classe 
   function cl_tipcalcexe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipcalcexe"); 
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
       $this->q83_codigo = ($this->q83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_codigo"]:$this->q83_codigo);
       $this->q83_tipcalc = ($this->q83_tipcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_tipcalc"]:$this->q83_tipcalc);
       $this->q83_anousu = ($this->q83_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_anousu"]:$this->q83_anousu);
       $this->q83_codven = ($this->q83_codven == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_codven"]:$this->q83_codven);
       $this->q83_cadvencdescsimples = ($this->q83_cadvencdescsimples == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_cadvencdescsimples"]:$this->q83_cadvencdescsimples);
     }else{
       $this->q83_codigo = ($this->q83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q83_codigo"]:$this->q83_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q83_codigo){ 
      $this->atualizacampos();
     if($this->q83_tipcalc == null ){ 
       $this->erro_sql = " Campo codigo do tipo de calculo nao Informado.";
       $this->erro_campo = "q83_tipcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q83_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q83_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q83_codven == null ){ 
       $this->erro_sql = " Campo codigo do vencimento nao Informado.";
       $this->erro_campo = "q83_codven";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q83_codigo == "" || $q83_codigo == null ){
       $result = db_query("select nextval('tipcalcexe_q83_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipcalcexe_q83_codigo_seq do campo: q83_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q83_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipcalcexe_q83_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q83_codigo)){
         $this->erro_sql = " Campo q83_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q83_codigo = $q83_codigo; 
       }
     }
     if(($this->q83_codigo == null) || ($this->q83_codigo == "") ){ 
       $this->erro_sql = " Campo q83_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipcalcexe(
                                       q83_codigo 
                                      ,q83_tipcalc 
                                      ,q83_anousu 
                                      ,q83_codven 
                                      ,q83_cadvencdescsimples 
                       )
                values (
                                $this->q83_codigo 
                               ,$this->q83_tipcalc 
                               ,$this->q83_anousu 
                               ,$this->q83_codven 
                               ,$this->q83_cadvencdescsimples 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados dos tipos de calculo por ano ($this->q83_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados dos tipos de calculo por ano j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados dos tipos de calculo por ano ($this->q83_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q83_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q83_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9799,'$this->q83_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1682,9799,'','".AddSlashes(pg_result($resaco,0,'q83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1682,9800,'','".AddSlashes(pg_result($resaco,0,'q83_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1682,9801,'','".AddSlashes(pg_result($resaco,0,'q83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1682,9802,'','".AddSlashes(pg_result($resaco,0,'q83_codven'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1682,18893,'','".AddSlashes(pg_result($resaco,0,'q83_cadvencdescsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q83_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tipcalcexe set ";
     $virgula = "";
     if(trim($this->q83_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q83_codigo"])){ 
       $sql  .= $virgula." q83_codigo = $this->q83_codigo ";
       $virgula = ",";
       if(trim($this->q83_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "q83_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q83_tipcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q83_tipcalc"])){ 
       $sql  .= $virgula." q83_tipcalc = $this->q83_tipcalc ";
       $virgula = ",";
       if(trim($this->q83_tipcalc) == null ){ 
         $this->erro_sql = " Campo codigo do tipo de calculo nao Informado.";
         $this->erro_campo = "q83_tipcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q83_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q83_anousu"])){ 
       $sql  .= $virgula." q83_anousu = $this->q83_anousu ";
       $virgula = ",";
       if(trim($this->q83_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q83_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q83_codven)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q83_codven"])){ 
       $sql  .= $virgula." q83_codven = $this->q83_codven ";
       $virgula = ",";
       if(trim($this->q83_codven) == null ){ 
         $this->erro_sql = " Campo codigo do vencimento nao Informado.";
         $this->erro_campo = "q83_codven";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q83_cadvencdescsimples)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q83_cadvencdescsimples"])){ 
        if(trim($this->q83_cadvencdescsimples)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q83_cadvencdescsimples"])){ 
           $this->q83_cadvencdescsimples = "0" ; 
        } 
       $sql  .= $virgula." q83_cadvencdescsimples = $this->q83_cadvencdescsimples ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q83_codigo!=null){
       $sql .= " q83_codigo = $this->q83_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q83_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9799,'$this->q83_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q83_codigo"]) || $this->q83_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1682,9799,'".AddSlashes(pg_result($resaco,$conresaco,'q83_codigo'))."','$this->q83_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q83_tipcalc"]) || $this->q83_tipcalc != "")
           $resac = db_query("insert into db_acount values($acount,1682,9800,'".AddSlashes(pg_result($resaco,$conresaco,'q83_tipcalc'))."','$this->q83_tipcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q83_anousu"]) || $this->q83_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1682,9801,'".AddSlashes(pg_result($resaco,$conresaco,'q83_anousu'))."','$this->q83_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q83_codven"]) || $this->q83_codven != "")
           $resac = db_query("insert into db_acount values($acount,1682,9802,'".AddSlashes(pg_result($resaco,$conresaco,'q83_codven'))."','$this->q83_codven',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q83_cadvencdescsimples"]) || $this->q83_cadvencdescsimples != "")
           $resac = db_query("insert into db_acount values($acount,1682,18893,'".AddSlashes(pg_result($resaco,$conresaco,'q83_cadvencdescsimples'))."','$this->q83_cadvencdescsimples',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados dos tipos de calculo por ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q83_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados dos tipos de calculo por ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q83_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q83_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9799,'$q83_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1682,9799,'','".AddSlashes(pg_result($resaco,$iresaco,'q83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1682,9800,'','".AddSlashes(pg_result($resaco,$iresaco,'q83_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1682,9801,'','".AddSlashes(pg_result($resaco,$iresaco,'q83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1682,9802,'','".AddSlashes(pg_result($resaco,$iresaco,'q83_codven'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1682,18893,'','".AddSlashes(pg_result($resaco,$iresaco,'q83_cadvencdescsimples'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipcalcexe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q83_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q83_codigo = $q83_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados dos tipos de calculo por ano nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q83_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados dos tipos de calculo por ano nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q83_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipcalcexe";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q83_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalcexe ";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = tipcalcexe.q83_codven and  cadvencdesc.q92_codigo = tipcalcexe.q83_cadvencdescsimples";
     $sql .= "      inner join tipcalc  on  tipcalc.q81_codigo = tipcalcexe.q83_tipcalc";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = cadvencdesc.q92_hist";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = cadvencdesc.q92_tipo";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql .= "      inner join geradesc  on  geradesc.q89_codigo = tipcalc.q81_gera";
     $sql2 = "";
     if($dbwhere==""){
       if($q83_codigo!=null ){
         $sql2 .= " where tipcalcexe.q83_codigo = $q83_codigo "; 
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
   function sql_query_file ( $q83_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalcexe ";
     $sql2 = "";
     if($dbwhere==""){
       if($q83_codigo!=null ){
         $sql2 .= " where tipcalcexe.q83_codigo = $q83_codigo "; 
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
   function sql_query_tipocalc ( $q83_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalcexe ";
     $sql .= "      inner join tipcalc  on  tipcalc.q81_codigo = tipcalcexe.q83_tipcalc";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql2 = "";
     if($dbwhere==""){
       if($q83_codigo!=null ){
         $sql2 .= " where tipcalcexe.q83_codigo = $q83_codigo "; 
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
   function sql_query_venc ( $q83_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalcexe ";
     $sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = tipcalcexe.q83_codven";
     $sql2 = "";
     if($dbwhere==""){
       if($q83_codigo!=null ){
         $sql2 .= " where tipcalcexe.q83_codigo = $q83_codigo "; 
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