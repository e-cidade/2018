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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_caractpreparo
class cl_mer_caractpreparo { 
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
   var $me06_i_codigo = 0; 
   var $me06_c_tempopreparo = null; 
   var $me06_c_graudificuldade = null; 
   var $me06_c_descr = null; 
   var $me06_i_cardapio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me06_i_codigo = int4 = Código 
                 me06_c_tempopreparo = char(10) = Tempo de Preparo 
                 me06_c_graudificuldade = char(20) = Grau Dificuldade 
                 me06_c_descr = char(20) = Descrição 
                 me06_i_cardapio = int4 = Cardápio 
                 ";
   //funcao construtor da classe 
   function cl_mer_caractpreparo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_caractpreparo"); 
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
       $this->me06_i_codigo = ($this->me06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_i_codigo"]:$this->me06_i_codigo);
       $this->me06_c_tempopreparo = ($this->me06_c_tempopreparo == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_c_tempopreparo"]:$this->me06_c_tempopreparo);
       $this->me06_c_graudificuldade = ($this->me06_c_graudificuldade == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_c_graudificuldade"]:$this->me06_c_graudificuldade);
       $this->me06_c_descr = ($this->me06_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_c_descr"]:$this->me06_c_descr);
       $this->me06_i_cardapio = ($this->me06_i_cardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_i_cardapio"]:$this->me06_i_cardapio);
     }else{
       $this->me06_i_codigo = ($this->me06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me06_i_codigo"]:$this->me06_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me06_i_codigo){ 
      $this->atualizacampos();
     if($this->me06_c_tempopreparo == null ){ 
       $this->erro_sql = " Campo Tempo de Preparo nao Informado.";
       $this->erro_campo = "me06_c_tempopreparo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me06_c_graudificuldade == null ){ 
       $this->erro_sql = " Campo Grau Dificuldade nao Informado.";
       $this->erro_campo = "me06_c_graudificuldade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me06_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "me06_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me06_i_cardapio == null ){ 
       $this->erro_sql = " Campo Cardápio nao Informado.";
       $this->erro_campo = "me06_i_cardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me06_i_codigo == "" || $me06_i_codigo == null ){
       $result = db_query("select nextval('mercaractpreparo_me06_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mercaractpreparo_me06_codigo_seq do campo: me06_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me06_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mercaractpreparo_me06_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me06_i_codigo)){
         $this->erro_sql = " Campo me06_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me06_i_codigo = $me06_i_codigo; 
       }
     }
     if(($this->me06_i_codigo == null) || ($this->me06_i_codigo == "") ){ 
       $this->erro_sql = " Campo me06_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_caractpreparo(
                                       me06_i_codigo 
                                      ,me06_c_tempopreparo 
                                      ,me06_c_graudificuldade 
                                      ,me06_c_descr 
                                      ,me06_i_cardapio 
                       )
                values (
                                $this->me06_i_codigo 
                               ,'$this->me06_c_tempopreparo' 
                               ,'$this->me06_c_graudificuldade' 
                               ,'$this->me06_c_descr' 
                               ,$this->me06_i_cardapio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_caractpreparo ($this->me06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_caractpreparo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_caractpreparo ($this->me06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me06_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me06_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12776,'$this->me06_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2238,12776,'','".AddSlashes(pg_result($resaco,0,'me06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2238,12778,'','".AddSlashes(pg_result($resaco,0,'me06_c_tempopreparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2238,12777,'','".AddSlashes(pg_result($resaco,0,'me06_c_graudificuldade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2238,12779,'','".AddSlashes(pg_result($resaco,0,'me06_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2238,12794,'','".AddSlashes(pg_result($resaco,0,'me06_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me06_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_caractpreparo set ";
     $virgula = "";
     if(trim($this->me06_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me06_i_codigo"])){ 
       $sql  .= $virgula." me06_i_codigo = $this->me06_i_codigo ";
       $virgula = ",";
       if(trim($this->me06_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me06_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me06_c_tempopreparo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me06_c_tempopreparo"])){ 
       $sql  .= $virgula." me06_c_tempopreparo = '$this->me06_c_tempopreparo' ";
       $virgula = ",";
       if(trim($this->me06_c_tempopreparo) == null ){ 
         $this->erro_sql = " Campo Tempo de Preparo nao Informado.";
         $this->erro_campo = "me06_c_tempopreparo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me06_c_graudificuldade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me06_c_graudificuldade"])){ 
       $sql  .= $virgula." me06_c_graudificuldade = '$this->me06_c_graudificuldade' ";
       $virgula = ",";
       if(trim($this->me06_c_graudificuldade) == null ){ 
         $this->erro_sql = " Campo Grau Dificuldade nao Informado.";
         $this->erro_campo = "me06_c_graudificuldade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me06_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me06_c_descr"])){ 
       $sql  .= $virgula." me06_c_descr = '$this->me06_c_descr' ";
       $virgula = ",";
       if(trim($this->me06_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "me06_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me06_i_cardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me06_i_cardapio"])){ 
       $sql  .= $virgula." me06_i_cardapio = $this->me06_i_cardapio ";
       $virgula = ",";
       if(trim($this->me06_i_cardapio) == null ){ 
         $this->erro_sql = " Campo Cardápio nao Informado.";
         $this->erro_campo = "me06_i_cardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me06_i_codigo!=null){
       $sql .= " me06_i_codigo = $this->me06_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me06_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12776,'$this->me06_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me06_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2238,12776,'".AddSlashes(pg_result($resaco,$conresaco,'me06_i_codigo'))."','$this->me06_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me06_c_tempopreparo"]))
           $resac = db_query("insert into db_acount values($acount,2238,12778,'".AddSlashes(pg_result($resaco,$conresaco,'me06_c_tempopreparo'))."','$this->me06_c_tempopreparo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me06_c_graudificuldade"]))
           $resac = db_query("insert into db_acount values($acount,2238,12777,'".AddSlashes(pg_result($resaco,$conresaco,'me06_c_graudificuldade'))."','$this->me06_c_graudificuldade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me06_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,2238,12779,'".AddSlashes(pg_result($resaco,$conresaco,'me06_c_descr'))."','$this->me06_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me06_i_cardapio"]))
           $resac = db_query("insert into db_acount values($acount,2238,12794,'".AddSlashes(pg_result($resaco,$conresaco,'me06_i_cardapio'))."','$this->me06_i_cardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_caractpreparo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_caractpreparo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me06_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me06_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12776,'$me06_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2238,12776,'','".AddSlashes(pg_result($resaco,$iresaco,'me06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2238,12778,'','".AddSlashes(pg_result($resaco,$iresaco,'me06_c_tempopreparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2238,12777,'','".AddSlashes(pg_result($resaco,$iresaco,'me06_c_graudificuldade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2238,12779,'','".AddSlashes(pg_result($resaco,$iresaco,'me06_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2238,12794,'','".AddSlashes(pg_result($resaco,$iresaco,'me06_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_caractpreparo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me06_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me06_i_codigo = $me06_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_caractpreparo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_caractpreparo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me06_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_caractpreparo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_caractpreparo ";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_caractpreparo.me06_i_cardapio";
     $sql2 = "";
     if($dbwhere==""){
       if($me06_i_codigo!=null ){
         $sql2 .= " where mer_caractpreparo.me06_i_codigo = $me06_i_codigo "; 
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
   function sql_query_file ( $me06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_caractpreparo ";
     $sql2 = "";
     if($dbwhere==""){
       if($me06_i_codigo!=null ){
         $sql2 .= " where mer_caractpreparo.me06_i_codigo = $me06_i_codigo "; 
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