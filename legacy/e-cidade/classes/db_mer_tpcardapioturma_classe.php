<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_tpcardapioturma
class cl_mer_tpcardapioturma { 
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
   var $me28_i_codigo = 0; 
   var $me28_i_serie = 0; 
   var $me28_i_cardapioescola = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me28_i_codigo = int4 = código 
                 me28_i_serie = int4 = código da série 
                 me28_i_cardapioescola = int4 = Cardapio escola 
                 ";
   //funcao construtor da classe 
   function cl_mer_tpcardapioturma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_tpcardapioturma"); 
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
       $this->me28_i_codigo = ($this->me28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me28_i_codigo"]:$this->me28_i_codigo);
       $this->me28_i_serie = ($this->me28_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["me28_i_serie"]:$this->me28_i_serie);
       $this->me28_i_cardapioescola = ($this->me28_i_cardapioescola == ""?@$GLOBALS["HTTP_POST_VARS"]["me28_i_cardapioescola"]:$this->me28_i_cardapioescola);
     }else{
       $this->me28_i_codigo = ($this->me28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me28_i_codigo"]:$this->me28_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me28_i_codigo){ 
      $this->atualizacampos();
     if($this->me28_i_serie == null ){ 
       $this->erro_sql = " Campo código da série nao Informado.";
       $this->erro_campo = "me28_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me28_i_cardapioescola == null ){ 
       $this->erro_sql = " Campo Cardapio escola nao Informado.";
       $this->erro_campo = "me28_i_cardapioescola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me28_i_codigo == "" || $me28_i_codigo == null ){
       $result = db_query("select nextval('mer_tpcardapioturma_me28_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_tpcardapioturma_me28_codigo_seq do campo: me28_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me28_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_tpcardapioturma_me28_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me28_i_codigo)){
         $this->erro_sql = " Campo me28_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me28_i_codigo = $me28_i_codigo; 
       }
     }
     if(($this->me28_i_codigo == null) || ($this->me28_i_codigo == "") ){ 
       $this->erro_sql = " Campo me28_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_tpcardapioturma(
                                       me28_i_codigo 
                                      ,me28_i_serie 
                                      ,me28_i_cardapioescola 
                       )
                values (
                                $this->me28_i_codigo 
                               ,$this->me28_i_serie 
                               ,$this->me28_i_cardapioescola 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tipo de cardapio por turma ($this->me28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tipo de cardapio por turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tipo de cardapio por turma ($this->me28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me28_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me28_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14170,'$this->me28_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2491,14170,'','".AddSlashes(pg_result($resaco,0,'me28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2491,14172,'','".AddSlashes(pg_result($resaco,0,'me28_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2491,17091,'','".AddSlashes(pg_result($resaco,0,'me28_i_cardapioescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me28_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_tpcardapioturma set ";
     $virgula = "";
     if(trim($this->me28_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me28_i_codigo"])){ 
       $sql  .= $virgula." me28_i_codigo = $this->me28_i_codigo ";
       $virgula = ",";
       if(trim($this->me28_i_codigo) == null ){ 
         $this->erro_sql = " Campo código nao Informado.";
         $this->erro_campo = "me28_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me28_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me28_i_serie"])){ 
       $sql  .= $virgula." me28_i_serie = $this->me28_i_serie ";
       $virgula = ",";
       if(trim($this->me28_i_serie) == null ){ 
         $this->erro_sql = " Campo código da série nao Informado.";
         $this->erro_campo = "me28_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me28_i_cardapioescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me28_i_cardapioescola"])){ 
       $sql  .= $virgula." me28_i_cardapioescola = $this->me28_i_cardapioescola ";
       $virgula = ",";
       if(trim($this->me28_i_cardapioescola) == null ){ 
         $this->erro_sql = " Campo Cardapio escola nao Informado.";
         $this->erro_campo = "me28_i_cardapioescola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me28_i_codigo!=null){
       $sql .= " me28_i_codigo = $this->me28_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me28_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14170,'$this->me28_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me28_i_codigo"]) || $this->me28_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2491,14170,'".AddSlashes(pg_result($resaco,$conresaco,'me28_i_codigo'))."','$this->me28_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me28_i_serie"]) || $this->me28_i_serie != "")
           $resac = db_query("insert into db_acount values($acount,2491,14172,'".AddSlashes(pg_result($resaco,$conresaco,'me28_i_serie'))."','$this->me28_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me28_i_cardapioescola"]) || $this->me28_i_cardapioescola != "")
           $resac = db_query("insert into db_acount values($acount,2491,17091,'".AddSlashes(pg_result($resaco,$conresaco,'me28_i_cardapioescola'))."','$this->me28_i_cardapioescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipo de cardapio por turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipo de cardapio por turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me28_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me28_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14170,'$me28_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2491,14170,'','".AddSlashes(pg_result($resaco,$iresaco,'me28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2491,14172,'','".AddSlashes(pg_result($resaco,$iresaco,'me28_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2491,17091,'','".AddSlashes(pg_result($resaco,$iresaco,'me28_i_cardapioescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_tpcardapioturma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me28_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me28_i_codigo = $me28_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipo de cardapio por turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipo de cardapio por turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me28_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_tpcardapioturma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     //$sql .= " from mer_tpcardapioturma ";
     //$sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_tpcardapioturma.me28_i_tipo";
     //$sql .= "      inner join serie  on  serie.ed11_i_codigo = mer_tpcardapioturma.me28_i_serie";
     //$sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_tipocardapio.me27_i_escola";
    // $sql .= "      inner join censoetapa  on  censoetapa.ed266_i_codigo = serie.ed11_i_codcenso";
    // $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     
     $sql .= " from mer_tpcardapioturma ";
     $sql .= "      inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_codigo = mer_tpcardapioturma.me28_i_cardapioescola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = mer_tpcardapioturma.me28_i_serie";
     $sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapioescola.me32_i_tipocardapio";
     //$sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($me28_i_codigo!=null ){
         $sql2 .= " where mer_tpcardapioturma.me28_i_codigo = $me28_i_codigo "; 
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
   function sql_query_file ( $me28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_tpcardapioturma ";
     $sql2 = "";
     if($dbwhere==""){
       if($me28_i_codigo!=null ){
         $sql2 .= " where mer_tpcardapioturma.me28_i_codigo = $me28_i_codigo "; 
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