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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcparamseqfiltrousuario
class cl_orcparamseqfiltrousuario { 
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
   var $o72_sequencial = 0; 
   var $o72_idusuario = 0; 
   var $o72_orcparamrel = 0; 
   var $o72_filtro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o72_sequencial = int4 = Código Sequencial 
                 o72_idusuario = int4 = Código do Usuário 
                 o72_orcparamrel = int4 = Codigo do Relatorio 
                 o72_filtro = text = Filtro 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqfiltrousuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqfiltrousuario"); 
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
       $this->o72_sequencial = ($this->o72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o72_sequencial"]:$this->o72_sequencial);
       $this->o72_idusuario = ($this->o72_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o72_idusuario"]:$this->o72_idusuario);
       $this->o72_orcparamrel = ($this->o72_orcparamrel == ""?@$GLOBALS["HTTP_POST_VARS"]["o72_orcparamrel"]:$this->o72_orcparamrel);
       $this->o72_filtro = ($this->o72_filtro == ""?@$GLOBALS["HTTP_POST_VARS"]["o72_filtro"]:$this->o72_filtro);
     }else{
       $this->o72_sequencial = ($this->o72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o72_sequencial"]:$this->o72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o72_sequencial){ 
      $this->atualizacampos();
     if($this->o72_idusuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "o72_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o72_orcparamrel == null ){ 
       $this->erro_sql = " Campo Codigo do Relatorio nao Informado.";
       $this->erro_campo = "o72_orcparamrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o72_sequencial == "" || $o72_sequencial == null ){
       $result = db_query("select nextval('orcparamseqfiltrousuario_o72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqfiltrousuario_o72_sequencial_seq do campo: o72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqfiltrousuario_o72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o72_sequencial)){
         $this->erro_sql = " Campo o72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o72_sequencial = $o72_sequencial; 
       }
     }
     if(($this->o72_sequencial == null) || ($this->o72_sequencial == "") ){ 
       $this->erro_sql = " Campo o72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqfiltrousuario(
                                       o72_sequencial 
                                      ,o72_idusuario 
                                      ,o72_orcparamrel 
                                      ,o72_filtro 
                       )
                values (
                                $this->o72_sequencial 
                               ,$this->o72_idusuario 
                               ,$this->o72_orcparamrel 
                               ,'$this->o72_filtro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Filtros dos usuarios para os relatorios ($this->o72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Filtros dos usuarios para os relatorios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Filtros dos usuarios para os relatorios ($this->o72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15644,'$this->o72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2743,15644,'','".AddSlashes(pg_result($resaco,0,'o72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2743,15645,'','".AddSlashes(pg_result($resaco,0,'o72_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2743,15646,'','".AddSlashes(pg_result($resaco,0,'o72_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2743,15647,'','".AddSlashes(pg_result($resaco,0,'o72_filtro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqfiltrousuario set ";
     $virgula = "";
     if(trim($this->o72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o72_sequencial"])){ 
       $sql  .= $virgula." o72_sequencial = $this->o72_sequencial ";
       $virgula = ",";
       if(trim($this->o72_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o72_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o72_idusuario"])){ 
       $sql  .= $virgula." o72_idusuario = $this->o72_idusuario ";
       $virgula = ",";
       if(trim($this->o72_idusuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "o72_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o72_orcparamrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o72_orcparamrel"])){ 
       $sql  .= $virgula." o72_orcparamrel = $this->o72_orcparamrel ";
       $virgula = ",";
       if(trim($this->o72_orcparamrel) == null ){ 
         $this->erro_sql = " Campo Codigo do Relatorio nao Informado.";
         $this->erro_campo = "o72_orcparamrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o72_filtro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o72_filtro"])){ 
       $sql  .= $virgula." o72_filtro = '$this->o72_filtro' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o72_sequencial!=null){
       $sql .= " o72_sequencial = $this->o72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15644,'$this->o72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o72_sequencial"]) || $this->o72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2743,15644,'".AddSlashes(pg_result($resaco,$conresaco,'o72_sequencial'))."','$this->o72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o72_idusuario"]) || $this->o72_idusuario != "")
           $resac = db_query("insert into db_acount values($acount,2743,15645,'".AddSlashes(pg_result($resaco,$conresaco,'o72_idusuario'))."','$this->o72_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o72_orcparamrel"]) || $this->o72_orcparamrel != "")
           $resac = db_query("insert into db_acount values($acount,2743,15646,'".AddSlashes(pg_result($resaco,$conresaco,'o72_orcparamrel'))."','$this->o72_orcparamrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o72_filtro"]) || $this->o72_filtro != "")
           $resac = db_query("insert into db_acount values($acount,2743,15647,'".AddSlashes(pg_result($resaco,$conresaco,'o72_filtro'))."','$this->o72_filtro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros dos usuarios para os relatorios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Filtros dos usuarios para os relatorios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15644,'$o72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2743,15644,'','".AddSlashes(pg_result($resaco,$iresaco,'o72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2743,15645,'','".AddSlashes(pg_result($resaco,$iresaco,'o72_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2743,15646,'','".AddSlashes(pg_result($resaco,$iresaco,'o72_orcparamrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2743,15647,'','".AddSlashes(pg_result($resaco,$iresaco,'o72_filtro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqfiltrousuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o72_sequencial = $o72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros dos usuarios para os relatorios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Filtros dos usuarios para os relatorios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqfiltrousuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqfiltrousuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = orcparamseqfiltrousuario.o72_idusuario";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseqfiltrousuario.o72_orcparamrel";
     $sql .= "      inner join orcparamrelgrupo  on  orcparamrelgrupo.o112_sequencial = orcparamrel.o42_orcparamrelgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($o72_sequencial!=null ){
         $sql2 .= " where orcparamseqfiltrousuario.o72_sequencial = $o72_sequencial "; 
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
   function sql_query_file ( $o72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqfiltrousuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($o72_sequencial!=null ){
         $sql2 .= " where orcparamseqfiltrousuario.o72_sequencial = $o72_sequencial "; 
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