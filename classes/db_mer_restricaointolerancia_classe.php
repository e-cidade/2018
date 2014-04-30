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
//CLASSE DA ENTIDADE mer_restricaointolerancia
class cl_mer_restricaointolerancia { 
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
   var $me34_i_codigo = 0; 
   var $me34_i_restricao = 0; 
   var $me34_i_intolerancia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me34_i_codigo = int4 = Código 
                 me34_i_restricao = int4 = Restrição 
                 me34_i_intolerancia = int4 = Intolerância Alimentar 
                 ";
   //funcao construtor da classe 
   function cl_mer_restricaointolerancia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_restricaointolerancia"); 
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
       $this->me34_i_codigo = ($this->me34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me34_i_codigo"]:$this->me34_i_codigo);
       $this->me34_i_restricao = ($this->me34_i_restricao == ""?@$GLOBALS["HTTP_POST_VARS"]["me34_i_restricao"]:$this->me34_i_restricao);
       $this->me34_i_intolerancia = ($this->me34_i_intolerancia == ""?@$GLOBALS["HTTP_POST_VARS"]["me34_i_intolerancia"]:$this->me34_i_intolerancia);
     }else{
       $this->me34_i_codigo = ($this->me34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me34_i_codigo"]:$this->me34_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me34_i_codigo){ 
      $this->atualizacampos();
     if($this->me34_i_restricao == null ){ 
       $this->erro_sql = " Campo Restrição nao Informado.";
       $this->erro_campo = "me34_i_restricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me34_i_intolerancia == null ){ 
       $this->erro_sql = " Campo Intolerância Alimentar nao Informado.";
       $this->erro_campo = "me34_i_intolerancia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me34_i_codigo == "" || $me34_i_codigo == null ){
       $result = db_query("select nextval('mer_restricaointolerancia_me34_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_restricaointolerancia_me34_i_codigo_seq do campo: me34_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me34_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_restricaointolerancia_me34_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me34_i_codigo)){
         $this->erro_sql = " Campo me34_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me34_i_codigo = $me34_i_codigo; 
       }
     }
     if(($this->me34_i_codigo == null) || ($this->me34_i_codigo == "") ){ 
       $this->erro_sql = " Campo me34_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_restricaointolerancia(
                                       me34_i_codigo 
                                      ,me34_i_restricao 
                                      ,me34_i_intolerancia 
                       )
                values (
                                $this->me34_i_codigo 
                               ,$this->me34_i_restricao 
                               ,$this->me34_i_intolerancia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_restricaointolerancia ($this->me34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_restricaointolerancia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_restricaointolerancia ($this->me34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me34_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me34_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17191,'$this->me34_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3039,17191,'','".AddSlashes(pg_result($resaco,0,'me34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3039,17192,'','".AddSlashes(pg_result($resaco,0,'me34_i_restricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3039,17193,'','".AddSlashes(pg_result($resaco,0,'me34_i_intolerancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me34_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_restricaointolerancia set ";
     $virgula = "";
     if(trim($this->me34_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me34_i_codigo"])){ 
       $sql  .= $virgula." me34_i_codigo = $this->me34_i_codigo ";
       $virgula = ",";
       if(trim($this->me34_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me34_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me34_i_restricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me34_i_restricao"])){ 
       $sql  .= $virgula." me34_i_restricao = $this->me34_i_restricao ";
       $virgula = ",";
       if(trim($this->me34_i_restricao) == null ){ 
         $this->erro_sql = " Campo Restrição nao Informado.";
         $this->erro_campo = "me34_i_restricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me34_i_intolerancia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me34_i_intolerancia"])){ 
       $sql  .= $virgula." me34_i_intolerancia = $this->me34_i_intolerancia ";
       $virgula = ",";
       if(trim($this->me34_i_intolerancia) == null ){ 
         $this->erro_sql = " Campo Intolerância Alimentar nao Informado.";
         $this->erro_campo = "me34_i_intolerancia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me34_i_codigo!=null){
       $sql .= " me34_i_codigo = $this->me34_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me34_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17191,'$this->me34_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me34_i_codigo"]) || $this->me34_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3039,17191,'".AddSlashes(pg_result($resaco,$conresaco,'me34_i_codigo'))."','$this->me34_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me34_i_restricao"]) || $this->me34_i_restricao != "")
           $resac = db_query("insert into db_acount values($acount,3039,17192,'".AddSlashes(pg_result($resaco,$conresaco,'me34_i_restricao'))."','$this->me34_i_restricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me34_i_intolerancia"]) || $this->me34_i_intolerancia != "")
           $resac = db_query("insert into db_acount values($acount,3039,17193,'".AddSlashes(pg_result($resaco,$conresaco,'me34_i_intolerancia'))."','$this->me34_i_intolerancia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_restricaointolerancia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_restricaointolerancia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me34_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me34_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17191,'$me34_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3039,17191,'','".AddSlashes(pg_result($resaco,$iresaco,'me34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3039,17192,'','".AddSlashes(pg_result($resaco,$iresaco,'me34_i_restricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3039,17193,'','".AddSlashes(pg_result($resaco,$iresaco,'me34_i_intolerancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_restricaointolerancia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me34_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me34_i_codigo = $me34_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_restricaointolerancia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_restricaointolerancia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me34_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_restricaointolerancia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restricaointolerancia ";
     $sql .= "      inner join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restricaointolerancia.me34_i_restricao";
     $sql .= "      inner join mer_intoleranciaalimentar  on  mer_intoleranciaalimentar.me33_i_codigo = mer_restricaointolerancia.me34_i_intolerancia";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = mer_restricao.me24_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($me34_i_codigo!=null ){
         $sql2 .= " where mer_restricaointolerancia.me34_i_codigo = $me34_i_codigo "; 
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
   function sql_query_refeicao ( $me34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restriitem ";
     $sql .= "      inner join mer_cardapioitem  on  mer_cardapioitem.me07_i_alimento = mer_restriitem.me25_i_alimento";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapioitem.me07_i_cardapio";
     $sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql .= "      inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_tipocardapio = mer_tipocardapio.me27_i_codigo";
     $sql .= "      inner join mer_tpcardapioturma  on  mer_tpcardapioturma.me28_i_cardapioescola = mer_cardapioescola.me32_i_codigo";
     $sql .= "      inner join mer_alimento  on  mer_alimento.me35_i_codigo = mer_restriitem.me25_i_alimento";
     $sql .= "      inner join mer_alimento as mer_alimentosub on  mer_alimentosub.me35_i_codigo = mer_restriitem.me25_i_alimentosub";     
     $sql .= "      inner join mer_restricao  on  mer_restricao.me24_i_codigo = mer_restriitem.me25_i_restricao";
     $sql .= "      inner join mer_restricaointolerancia  on  mer_restricaointolerancia.me34_i_restricao = mer_restricao.me24_i_codigo";
     $sql .= "      inner join mer_intoleranciaalimentar  on  mer_intoleranciaalimentar.me33_i_codigo = mer_restricaointolerancia.me34_i_intolerancia";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = mer_restricao.me24_i_aluno";
     $sql .= "      inner join matricula  on  matricula.ed60_i_aluno = aluno.ed47_i_codigo";
     $sql .= "      inner join matriculaserie  on  matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo";
     $sql .= "                                and  matriculaserie.ed221_i_serie = mer_tpcardapioturma.me28_i_serie";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "                        and  escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($me34_i_codigo!=null ){
         $sql2 .= " where mer_restricaointolerancia.me34_i_codigo = $me34_i_codigo "; 
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
   function sql_query_file ( $me34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_restricaointolerancia ";
     $sql2 = "";
     if($dbwhere==""){
       if($me34_i_codigo!=null ){
         $sql2 .= " where mer_restricaointolerancia.me34_i_codigo = $me34_i_codigo "; 
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