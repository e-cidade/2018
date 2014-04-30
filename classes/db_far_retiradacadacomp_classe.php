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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_retiradacadacomp
class cl_far_retiradacadacomp { 
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
   var $fa55_i_codigo = 0; 
   var $fa55_i_cadacomp = 0; 
   var $fa55_i_retirada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa55_i_codigo = int4 = Código 
                 fa55_i_cadacomp = int4 = Cadastro / Acompanhamento 
                 fa55_i_retirada = int4 = Retirada 
                 ";
   //funcao construtor da classe 
   function cl_far_retiradacadacomp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_retiradacadacomp"); 
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
       $this->fa55_i_codigo = ($this->fa55_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa55_i_codigo"]:$this->fa55_i_codigo);
       $this->fa55_i_cadacomp = ($this->fa55_i_cadacomp == ""?@$GLOBALS["HTTP_POST_VARS"]["fa55_i_cadacomp"]:$this->fa55_i_cadacomp);
       $this->fa55_i_retirada = ($this->fa55_i_retirada == ""?@$GLOBALS["HTTP_POST_VARS"]["fa55_i_retirada"]:$this->fa55_i_retirada);
     }else{
       $this->fa55_i_codigo = ($this->fa55_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa55_i_codigo"]:$this->fa55_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa55_i_codigo){ 
      $this->atualizacampos();
     if($this->fa55_i_cadacomp == null ){ 
       $this->erro_sql = " Campo Cadastro / Acompanhamento nao Informado.";
       $this->erro_campo = "fa55_i_cadacomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa55_i_retirada == null ){ 
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "fa55_i_retirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa55_i_codigo == "" || $fa55_i_codigo == null ){
       $result = db_query("select nextval('far_retiradacadacomp_fa55_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_retiradacadacomp_fa55_i_codigo_seq do campo: fa55_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa55_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_retiradacadacomp_fa55_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa55_i_codigo)){
         $this->erro_sql = " Campo fa55_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa55_i_codigo = $fa55_i_codigo; 
       }
     }
     if(($this->fa55_i_codigo == null) || ($this->fa55_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa55_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_retiradacadacomp(
                                       fa55_i_codigo 
                                      ,fa55_i_cadacomp 
                                      ,fa55_i_retirada 
                       )
                values (
                                $this->fa55_i_codigo 
                               ,$this->fa55_i_cadacomp 
                               ,$this->fa55_i_retirada 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_retiradacadacomp ($this->fa55_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_retiradacadacomp já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_retiradacadacomp ($this->fa55_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa55_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa55_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17272,'$this->fa55_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3057,17272,'','".AddSlashes(pg_result($resaco,0,'fa55_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3057,17273,'','".AddSlashes(pg_result($resaco,0,'fa55_i_cadacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3057,17274,'','".AddSlashes(pg_result($resaco,0,'fa55_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa55_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_retiradacadacomp set ";
     $virgula = "";
     if(trim($this->fa55_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_codigo"])){ 
       $sql  .= $virgula." fa55_i_codigo = $this->fa55_i_codigo ";
       $virgula = ",";
       if(trim($this->fa55_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa55_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa55_i_cadacomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_cadacomp"])){ 
       $sql  .= $virgula." fa55_i_cadacomp = $this->fa55_i_cadacomp ";
       $virgula = ",";
       if(trim($this->fa55_i_cadacomp) == null ){ 
         $this->erro_sql = " Campo Cadastro / Acompanhamento nao Informado.";
         $this->erro_campo = "fa55_i_cadacomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa55_i_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_retirada"])){ 
       $sql  .= $virgula." fa55_i_retirada = $this->fa55_i_retirada ";
       $virgula = ",";
       if(trim($this->fa55_i_retirada) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "fa55_i_retirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa55_i_codigo!=null){
       $sql .= " fa55_i_codigo = $this->fa55_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa55_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17272,'$this->fa55_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_codigo"]) || $this->fa55_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3057,17272,'".AddSlashes(pg_result($resaco,$conresaco,'fa55_i_codigo'))."','$this->fa55_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_cadacomp"]) || $this->fa55_i_cadacomp != "")
           $resac = db_query("insert into db_acount values($acount,3057,17273,'".AddSlashes(pg_result($resaco,$conresaco,'fa55_i_cadacomp'))."','$this->fa55_i_cadacomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa55_i_retirada"]) || $this->fa55_i_retirada != "")
           $resac = db_query("insert into db_acount values($acount,3057,17274,'".AddSlashes(pg_result($resaco,$conresaco,'fa55_i_retirada'))."','$this->fa55_i_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradacadacomp nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa55_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradacadacomp nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa55_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa55_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17272,'$fa55_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3057,17272,'','".AddSlashes(pg_result($resaco,$iresaco,'fa55_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3057,17273,'','".AddSlashes(pg_result($resaco,$iresaco,'fa55_i_cadacomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3057,17274,'','".AddSlashes(pg_result($resaco,$iresaco,'fa55_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_retiradacadacomp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa55_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa55_i_codigo = $fa55_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradacadacomp nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa55_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradacadacomp nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa55_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa55_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_retiradacadacomp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa55_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradacadacomp ";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradacadacomp.fa55_i_retirada";
     $sql .= "      inner join far_cadacomppachiperdia  on  far_cadacomppachiperdia.fa50_i_codigo = far_retiradacadacomp.fa55_i_cadacomp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
     $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_retirada.fa04_i_cgsund";
     $sql .= "      inner join sau_triagemavulsa  on  sau_triagemavulsa.s152_i_codigo = far_cadacomppachiperdia.fa50_i_triagem";
     $sql .= "      inner join cgs_und  as a on   a.z01_i_cgsund = far_cadacomppachiperdia.fa50_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($fa55_i_codigo!=null ){
         $sql2 .= " where far_retiradacadacomp.fa55_i_codigo = $fa55_i_codigo "; 
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
   function sql_query_file ( $fa55_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradacadacomp ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa55_i_codigo!=null ){
         $sql2 .= " where far_retiradacadacomp.fa55_i_codigo = $fa55_i_codigo "; 
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