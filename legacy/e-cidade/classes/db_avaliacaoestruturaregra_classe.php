<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: escola
//CLASSE DA ENTIDADE avaliacaoestruturaregra
class cl_avaliacaoestruturaregra { 
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
   var $ed318_sequencial = 0; 
   var $ed318_avaliacaoestruturanota = 0; 
   var $ed318_regraarredondamento = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed318_sequencial = int4 = Código 
                 ed318_avaliacaoestruturanota = int4 = Avaliação Estrutura Nota 
                 ed318_regraarredondamento = int4 = Regra de Arredondamento 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoestruturaregra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoestruturaregra"); 
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
       $this->ed318_sequencial = ($this->ed318_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed318_sequencial"]:$this->ed318_sequencial);
       $this->ed318_avaliacaoestruturanota = ($this->ed318_avaliacaoestruturanota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed318_avaliacaoestruturanota"]:$this->ed318_avaliacaoestruturanota);
       $this->ed318_regraarredondamento = ($this->ed318_regraarredondamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed318_regraarredondamento"]:$this->ed318_regraarredondamento);
     }else{
       $this->ed318_sequencial = ($this->ed318_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed318_sequencial"]:$this->ed318_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed318_sequencial){ 
      $this->atualizacampos();
     if($this->ed318_avaliacaoestruturanota == null ){ 
       $this->erro_sql = " Campo Avaliação Estrutura Nota não informado.";
       $this->erro_campo = "ed318_avaliacaoestruturanota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed318_regraarredondamento == null ){ 
       $this->ed318_regraarredondamento = "null";
     }
     if($ed318_sequencial == "" || $ed318_sequencial == null ){
       $result = db_query("select nextval('avaliacaoestruturaregra_ed318_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoestruturaregra_ed318_sequencial_seq do campo: ed318_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed318_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoestruturaregra_ed318_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed318_sequencial)){
         $this->erro_sql = " Campo ed318_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed318_sequencial = $ed318_sequencial; 
       }
     }
     if(($this->ed318_sequencial == null) || ($this->ed318_sequencial == "") ){ 
       $this->erro_sql = " Campo ed318_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoestruturaregra(
                                       ed318_sequencial 
                                      ,ed318_avaliacaoestruturanota 
                                      ,ed318_regraarredondamento 
                       )
                values (
                                $this->ed318_sequencial 
                               ,$this->ed318_avaliacaoestruturanota 
                               ,$this->ed318_regraarredondamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação da Estrutura e Regra ($this->ed318_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação da Estrutura e Regra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação da Estrutura e Regra ($this->ed318_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed318_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed318_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18954,'$this->ed318_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3372,18954,'','".AddSlashes(pg_result($resaco,0,'ed318_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3372,18955,'','".AddSlashes(pg_result($resaco,0,'ed318_avaliacaoestruturanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3372,18956,'','".AddSlashes(pg_result($resaco,0,'ed318_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed318_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoestruturaregra set ";
     $virgula = "";
     if(trim($this->ed318_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed318_sequencial"])){ 
       $sql  .= $virgula." ed318_sequencial = $this->ed318_sequencial ";
       $virgula = ",";
       if(trim($this->ed318_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed318_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed318_avaliacaoestruturanota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed318_avaliacaoestruturanota"])){ 
       $sql  .= $virgula." ed318_avaliacaoestruturanota = $this->ed318_avaliacaoestruturanota ";
       $virgula = ",";
       if(trim($this->ed318_avaliacaoestruturanota) == null ){ 
         $this->erro_sql = " Campo Avaliação Estrutura Nota não informado.";
         $this->erro_campo = "ed318_avaliacaoestruturanota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed318_regraarredondamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed318_regraarredondamento"])){ 
        if(trim($this->ed318_regraarredondamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed318_regraarredondamento"])){ 
           $this->ed318_regraarredondamento = "null" ; 
        } 
       $sql  .= $virgula." ed318_regraarredondamento = $this->ed318_regraarredondamento ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed318_sequencial!=null){
       $sql .= " ed318_sequencial = $this->ed318_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed318_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18954,'$this->ed318_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed318_sequencial"]) || $this->ed318_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3372,18954,'".AddSlashes(pg_result($resaco,$conresaco,'ed318_sequencial'))."','$this->ed318_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed318_avaliacaoestruturanota"]) || $this->ed318_avaliacaoestruturanota != "")
             $resac = db_query("insert into db_acount values($acount,3372,18955,'".AddSlashes(pg_result($resaco,$conresaco,'ed318_avaliacaoestruturanota'))."','$this->ed318_avaliacaoestruturanota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed318_regraarredondamento"]) || $this->ed318_regraarredondamento != "")
             $resac = db_query("insert into db_acount values($acount,3372,18956,'".AddSlashes(pg_result($resaco,$conresaco,'ed318_regraarredondamento'))."','$this->ed318_regraarredondamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação da Estrutura e Regra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed318_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação da Estrutura e Regra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed318_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed318_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed318_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed318_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18954,'$ed318_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3372,18954,'','".AddSlashes(pg_result($resaco,$iresaco,'ed318_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3372,18955,'','".AddSlashes(pg_result($resaco,$iresaco,'ed318_avaliacaoestruturanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3372,18956,'','".AddSlashes(pg_result($resaco,$iresaco,'ed318_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoestruturaregra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed318_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed318_sequencial = $ed318_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação da Estrutura e Regra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed318_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação da Estrutura e Regra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed318_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed318_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoestruturaregra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed318_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturaregra ";
     $sql .= "      inner join avaliacaoestruturanota  on  avaliacaoestruturanota.ed315_sequencial = avaliacaoestruturaregra.ed318_avaliacaoestruturanota";
     $sql .= "      left  join regraarredondamento  on  regraarredondamento.ed316_sequencial = avaliacaoestruturaregra.ed318_regraarredondamento";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = avaliacaoestruturanota.ed315_db_estrutura";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = avaliacaoestruturanota.ed315_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed318_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturaregra.ed318_sequencial = $ed318_sequencial "; 
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
   function sql_query_file ( $ed318_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturaregra ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed318_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturaregra.ed318_sequencial = $ed318_sequencial "; 
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