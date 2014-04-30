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

//MODULO: cadastro
//CLASSE DA ENTIDADE facevalor
class cl_facevalor { 
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
   var $j81_codigo = 0; 
   var $j81_face = 0; 
   var $j81_anousu = 0; 
   var $j81_valorterreno = 0; 
   var $j81_valorconstr = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j81_codigo = int4 = Codigo sequencial 
                 j81_face = int4 = Cód. Face 
                 j81_anousu = int4 = Exrcício 
                 j81_valorterreno = float8 = Valor m2 terreno 
                 j81_valorconstr = float8 = Valor m2 construção 
                 ";
   //funcao construtor da classe 
   function cl_facevalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("facevalor"); 
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
       $this->j81_codigo = ($this->j81_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_codigo"]:$this->j81_codigo);
       $this->j81_face = ($this->j81_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_face"]:$this->j81_face);
       $this->j81_anousu = ($this->j81_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_anousu"]:$this->j81_anousu);
       $this->j81_valorterreno = ($this->j81_valorterreno == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_valorterreno"]:$this->j81_valorterreno);
       $this->j81_valorconstr = ($this->j81_valorconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_valorconstr"]:$this->j81_valorconstr);
     }else{
       $this->j81_codigo = ($this->j81_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j81_codigo"]:$this->j81_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j81_codigo){ 
      $this->atualizacampos();
     if($this->j81_face == null ){ 
       $this->erro_sql = " Campo Cód. Face nao Informado.";
       $this->erro_campo = "j81_face";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j81_anousu == null ){ 
       $this->erro_sql = " Campo Exrcício nao Informado.";
       $this->erro_campo = "j81_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j81_valorterreno == null ){ 
       $this->erro_sql = " Campo Valor m2 terreno nao Informado.";
       $this->erro_campo = "j81_valorterreno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j81_valorconstr == null ){ 
       $this->erro_sql = " Campo Valor m2 construção nao Informado.";
       $this->erro_campo = "j81_valorconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j81_codigo == "" || $j81_codigo == null ){
       $result = db_query("select nextval('facevalor_j81_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: facevalor_j81_codigo_seq do campo: j81_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j81_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from facevalor_j81_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j81_codigo)){
         $this->erro_sql = " Campo j81_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j81_codigo = $j81_codigo; 
       }
     }
     if(($this->j81_codigo == null) || ($this->j81_codigo == "") ){ 
       $this->erro_sql = " Campo j81_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into facevalor(
                                       j81_codigo 
                                      ,j81_face 
                                      ,j81_anousu 
                                      ,j81_valorterreno 
                                      ,j81_valorconstr 
                       )
                values (
                                $this->j81_codigo 
                               ,$this->j81_face 
                               ,$this->j81_anousu 
                               ,$this->j81_valorterreno 
                               ,$this->j81_valorconstr 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores da face por ano ($this->j81_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores da face por ano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores da face por ano ($this->j81_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j81_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j81_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9663,'$this->j81_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1663,9663,'','".AddSlashes(pg_result($resaco,0,'j81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1663,9664,'','".AddSlashes(pg_result($resaco,0,'j81_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1663,9667,'','".AddSlashes(pg_result($resaco,0,'j81_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1663,9665,'','".AddSlashes(pg_result($resaco,0,'j81_valorterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1663,9666,'','".AddSlashes(pg_result($resaco,0,'j81_valorconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j81_codigo=null) { 
      $this->atualizacampos();
     $sql = " update facevalor set ";
     $virgula = "";
     if(trim($this->j81_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j81_codigo"])){ 
       $sql  .= $virgula." j81_codigo = $this->j81_codigo ";
       $virgula = ",";
       if(trim($this->j81_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "j81_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j81_face)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j81_face"])){ 
       $sql  .= $virgula." j81_face = $this->j81_face ";
       $virgula = ",";
       if(trim($this->j81_face) == null ){ 
         $this->erro_sql = " Campo Cód. Face nao Informado.";
         $this->erro_campo = "j81_face";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j81_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j81_anousu"])){ 
       $sql  .= $virgula." j81_anousu = $this->j81_anousu ";
       $virgula = ",";
       if(trim($this->j81_anousu) == null ){ 
         $this->erro_sql = " Campo Exrcício nao Informado.";
         $this->erro_campo = "j81_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j81_valorterreno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j81_valorterreno"])){ 
       $sql  .= $virgula." j81_valorterreno = $this->j81_valorterreno ";
       $virgula = ",";
       if(trim($this->j81_valorterreno) == null ){ 
         $this->erro_sql = " Campo Valor m2 terreno nao Informado.";
         $this->erro_campo = "j81_valorterreno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j81_valorconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j81_valorconstr"])){ 
       $sql  .= $virgula." j81_valorconstr = $this->j81_valorconstr ";
       $virgula = ",";
       if(trim($this->j81_valorconstr) == null ){ 
         $this->erro_sql = " Campo Valor m2 construção nao Informado.";
         $this->erro_campo = "j81_valorconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j81_codigo!=null){
       $sql .= " j81_codigo = $this->j81_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j81_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9663,'$this->j81_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j81_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1663,9663,'".AddSlashes(pg_result($resaco,$conresaco,'j81_codigo'))."','$this->j81_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j81_face"]))
           $resac = db_query("insert into db_acount values($acount,1663,9664,'".AddSlashes(pg_result($resaco,$conresaco,'j81_face'))."','$this->j81_face',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j81_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1663,9667,'".AddSlashes(pg_result($resaco,$conresaco,'j81_anousu'))."','$this->j81_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j81_valorterreno"]))
           $resac = db_query("insert into db_acount values($acount,1663,9665,'".AddSlashes(pg_result($resaco,$conresaco,'j81_valorterreno'))."','$this->j81_valorterreno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j81_valorconstr"]))
           $resac = db_query("insert into db_acount values($acount,1663,9666,'".AddSlashes(pg_result($resaco,$conresaco,'j81_valorconstr'))."','$this->j81_valorconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores da face por ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j81_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores da face por ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j81_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j81_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9663,'$j81_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1663,9663,'','".AddSlashes(pg_result($resaco,$iresaco,'j81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1663,9664,'','".AddSlashes(pg_result($resaco,$iresaco,'j81_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1663,9667,'','".AddSlashes(pg_result($resaco,$iresaco,'j81_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1663,9665,'','".AddSlashes(pg_result($resaco,$iresaco,'j81_valorterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1663,9666,'','".AddSlashes(pg_result($resaco,$iresaco,'j81_valorconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from facevalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j81_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j81_codigo = $j81_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores da face por ano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j81_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores da face por ano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j81_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:facevalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j81_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from facevalor ";
     $sql .= "      inner join face  on  face.j37_face = facevalor.j81_face";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = face.j37_codigo";
     $sql .= "      inner join setor  on  setor.j30_codi = face.j37_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($j81_codigo!=null ){
         $sql2 .= " where facevalor.j81_codigo = $j81_codigo "; 
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
   function sql_query_file ( $j81_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from facevalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($j81_codigo!=null ){
         $sql2 .= " where facevalor.j81_codigo = $j81_codigo "; 
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