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

//MODULO: dbicms
//CLASSE DA ENTIDADE guiabv
class cl_guiabv { 
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
   var $anousu = 0; 
   var $tr = null; 
   var $cgcter = null; 
   var $cgcte = null; 
   var $ref = null; 
   var $valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 anousu = int4 = Exercício 
                 tr = char(2) = Tipo de Registro 
                 cgcter = char(3) = Codigo do Município 
                 cgcte = char(7) = Cadastro no Tesouro do Estado 
                 ref = char(3) = Referência 
                 valor = float8 = valor 
                 ";
   //funcao construtor da classe 
   function cl_guiabv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("guiabv"); 
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
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->tr = ($this->tr == ""?@$GLOBALS["HTTP_POST_VARS"]["tr"]:$this->tr);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
       $this->ref = ($this->ref == ""?@$GLOBALS["HTTP_POST_VARS"]["ref"]:$this->ref);
       $this->valor = ($this->valor == ""?@$GLOBALS["HTTP_POST_VARS"]["valor"]:$this->valor);
     }else{
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->tr = ($this->tr == ""?@$GLOBALS["HTTP_POST_VARS"]["tr"]:$this->tr);
       $this->cgcter = ($this->cgcter == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcter"]:$this->cgcter);
       $this->cgcte = ($this->cgcte == ""?@$GLOBALS["HTTP_POST_VARS"]["cgcte"]:$this->cgcte);
       $this->ref = ($this->ref == ""?@$GLOBALS["HTTP_POST_VARS"]["ref"]:$this->ref);
     }
   }
   // funcao para inclusao
   function incluir ($anousu,$tr,$cgcter,$cgcte,$ref){ 
      $this->atualizacampos();
     if($this->valor == null ){ 
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->anousu = $anousu; 
       $this->tr = $tr; 
       $this->cgcter = $cgcter; 
       $this->cgcte = $cgcte; 
       $this->ref = $ref; 
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->tr == null) || ($this->tr == "") ){ 
       $this->erro_sql = " Campo tr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcter == null) || ($this->cgcter == "") ){ 
       $this->erro_sql = " Campo cgcter nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->cgcte == null) || ($this->cgcte == "") ){ 
       $this->erro_sql = " Campo cgcte nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->ref == null) || ($this->ref == "") ){ 
       $this->erro_sql = " Campo ref nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into guiabv(
                                       anousu 
                                      ,tr 
                                      ,cgcter 
                                      ,cgcte 
                                      ,ref 
                                      ,valor 
                       )
                values (
                                $this->anousu 
                               ,'$this->tr' 
                               ,'$this->cgcter' 
                               ,'$this->cgcte' 
                               ,'$this->ref' 
                               ,$this->valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Guiabv ($this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Guiabv já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Guiabv ($this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->tr,$this->cgcter,$this->cgcte,$this->ref));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,2304,'$this->tr','I')");
       $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','I')");
       $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','I')");
       $resac = db_query("insert into db_acountkey values($acount,2305,'$this->ref','I')");
       $resac = db_query("insert into db_acount values($acount,364,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,364,2304,'','".AddSlashes(pg_result($resaco,0,'tr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,364,2275,'','".AddSlashes(pg_result($resaco,0,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,364,2280,'','".AddSlashes(pg_result($resaco,0,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,364,2305,'','".AddSlashes(pg_result($resaco,0,'ref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,364,556,'','".AddSlashes(pg_result($resaco,0,'valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($anousu=null,$tr=null,$cgcter=null,$cgcte=null,$ref=null) { 
      $this->atualizacampos();
     $sql = " update guiabv set ";
     $virgula = "";
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr"])){ 
       $sql  .= $virgula." tr = '$this->tr' ";
       $virgula = ",";
       if(trim($this->tr) == null ){ 
         $this->erro_sql = " Campo Tipo de Registro nao Informado.";
         $this->erro_campo = "tr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcter"])){ 
       $sql  .= $virgula." cgcter = '$this->cgcter' ";
       $virgula = ",";
       if(trim($this->cgcter) == null ){ 
         $this->erro_sql = " Campo Codigo do Município nao Informado.";
         $this->erro_campo = "cgcter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cgcte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cgcte"])){ 
       $sql  .= $virgula." cgcte = '$this->cgcte' ";
       $virgula = ",";
       if(trim($this->cgcte) == null ){ 
         $this->erro_sql = " Campo Cadastro no Tesouro do Estado nao Informado.";
         $this->erro_campo = "cgcte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ref"])){ 
       $sql  .= $virgula." ref = '$this->ref' ";
       $virgula = ",";
       if(trim($this->ref) == null ){ 
         $this->erro_sql = " Campo Referência nao Informado.";
         $this->erro_campo = "ref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["valor"])){ 
       $sql  .= $virgula." valor = $this->valor ";
       $virgula = ",";
       if(trim($this->valor) == null ){ 
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($anousu!=null){
       $sql .= " anousu = $this->anousu";
     }
     if($tr!=null){
       $sql .= " and  tr = '$this->tr'";
     }
     if($cgcter!=null){
       $sql .= " and  cgcter = '$this->cgcter'";
     }
     if($cgcte!=null){
       $sql .= " and  cgcte = '$this->cgcte'";
     }
     if($ref!=null){
       $sql .= " and  ref = '$this->ref'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->anousu,$this->tr,$this->cgcter,$this->cgcte,$this->ref));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,2304,'$this->tr','A')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$this->cgcter','A')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$this->cgcte','A')");
         $resac = db_query("insert into db_acountkey values($acount,2305,'$this->ref','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]))
           $resac = db_query("insert into db_acount values($acount,364,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr"]))
           $resac = db_query("insert into db_acount values($acount,364,2304,'".AddSlashes(pg_result($resaco,$conresaco,'tr'))."','$this->tr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcter"]))
           $resac = db_query("insert into db_acount values($acount,364,2275,'".AddSlashes(pg_result($resaco,$conresaco,'cgcter'))."','$this->cgcter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cgcte"]))
           $resac = db_query("insert into db_acount values($acount,364,2280,'".AddSlashes(pg_result($resaco,$conresaco,'cgcte'))."','$this->cgcte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ref"]))
           $resac = db_query("insert into db_acount values($acount,364,2305,'".AddSlashes(pg_result($resaco,$conresaco,'ref'))."','$this->ref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["valor"]))
           $resac = db_query("insert into db_acount values($acount,364,556,'".AddSlashes(pg_result($resaco,$conresaco,'valor'))."','$this->valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guiabv nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabv nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->anousu."-".$this->tr."-".$this->cgcter."-".$this->cgcte."-".$this->ref;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($anousu=null,$tr=null,$cgcter=null,$cgcte=null,$ref=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($anousu,$tr,$cgcter,$cgcte,$ref));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,2304,'$tr','E')");
         $resac = db_query("insert into db_acountkey values($acount,2275,'$cgcter','E')");
         $resac = db_query("insert into db_acountkey values($acount,2280,'$cgcte','E')");
         $resac = db_query("insert into db_acountkey values($acount,2305,'$ref','E')");
         $resac = db_query("insert into db_acount values($acount,364,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,364,2304,'','".AddSlashes(pg_result($resaco,$iresaco,'tr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,364,2275,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,364,2280,'','".AddSlashes(pg_result($resaco,$iresaco,'cgcte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,364,2305,'','".AddSlashes(pg_result($resaco,$iresaco,'ref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,364,556,'','".AddSlashes(pg_result($resaco,$iresaco,'valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from guiabv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($tr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr = '$tr' ";
        }
        if($cgcter != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcter = '$cgcter' ";
        }
        if($cgcte != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cgcte = '$cgcte' ";
        }
        if($ref != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ref = '$ref' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Guiabv nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte."-".$ref;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Guiabv nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte."-".$ref;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$anousu."-".$tr."-".$cgcter."-".$cgcte."-".$ref;
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
        $this->erro_sql   = "Record Vazio na Tabela:guiabv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>