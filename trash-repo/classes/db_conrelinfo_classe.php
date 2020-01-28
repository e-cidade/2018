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

//MODULO: Contabilidade
//CLASSE DA ENTIDADE conrelinfo
class cl_conrelinfo { 
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
   var $c83_codigo = 0; 
   var $c83_codrel = 0; 
   var $c83_variavel = null; 
   var $c83_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c83_codigo = int4 = codigo sequencial 
                 c83_codrel = int4 = codigo do relatorio 
                 c83_variavel = char(150) = variavel do relatorio 
                 c83_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_conrelinfo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conrelinfo"); 
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
       $this->c83_codigo = ($this->c83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_codigo"]:$this->c83_codigo);
       $this->c83_codrel = ($this->c83_codrel == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_codrel"]:$this->c83_codrel);
       $this->c83_variavel = ($this->c83_variavel == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_variavel"]:$this->c83_variavel);
       $this->c83_anousu = ($this->c83_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_anousu"]:$this->c83_anousu);
     }else{
       $this->c83_codigo = ($this->c83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_codigo"]:$this->c83_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($c83_codigo){ 
      $this->atualizacampos();
     if($this->c83_codrel == null ){ 
       $this->erro_sql = " Campo codigo do relatorio nao Informado.";
       $this->erro_campo = "c83_codrel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c83_variavel == null ){ 
       $this->erro_sql = " Campo variavel do relatorio nao Informado.";
       $this->erro_campo = "c83_variavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c83_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c83_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c83_codigo == "" || $c83_codigo == null ){
       $result = db_query("select nextval('conrelinfo_c83_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conrelinfo_c83_codigo_seq do campo: c83_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c83_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conrelinfo_c83_codigo_seq");
			 if(($result != false) && (pg_result($result,0,0) < $c83_codigo)){
         $this->erro_sql = " Campo c83_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c83_codigo = $c83_codigo; 
       }
     }
     if(($this->c83_codigo == null) || ($this->c83_codigo == "") ){ 
       $this->erro_sql = " Campo c83_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conrelinfo(
                                       c83_codigo 
                                      ,c83_codrel 
                                      ,c83_variavel 
                                      ,c83_anousu 
                       )
                values (
                                $this->c83_codigo 
                               ,$this->c83_codrel 
                               ,'$this->c83_variavel' 
                               ,$this->c83_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "c83 ($this->c83_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "c83 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "c83 ($this->c83_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c83_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7264,'$this->c83_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1204,7264,'','".AddSlashes(pg_result($resaco,0,'c83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1204,7265,'','".AddSlashes(pg_result($resaco,0,'c83_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1204,7266,'','".AddSlashes(pg_result($resaco,0,'c83_variavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1204,8640,'','".AddSlashes(pg_result($resaco,0,'c83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c83_codigo=null) { 
      $this->atualizacampos();
     $sql = " update conrelinfo set ";
     $virgula = "";
     if(trim($this->c83_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_codigo"])){ 
       $sql  .= $virgula." c83_codigo = $this->c83_codigo ";
       $virgula = ",";
       if(trim($this->c83_codigo) == null ){ 
         $this->erro_sql = " Campo codigo sequencial nao Informado.";
         $this->erro_campo = "c83_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_codrel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_codrel"])){ 
       $sql  .= $virgula." c83_codrel = $this->c83_codrel ";
       $virgula = ",";
       if(trim($this->c83_codrel) == null ){ 
         $this->erro_sql = " Campo codigo do relatorio nao Informado.";
         $this->erro_campo = "c83_codrel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_variavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_variavel"])){ 
       $sql  .= $virgula." c83_variavel = '$this->c83_variavel' ";
       $virgula = ",";
       if(trim($this->c83_variavel) == null ){ 
         $this->erro_sql = " Campo variavel do relatorio nao Informado.";
         $this->erro_campo = "c83_variavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_anousu"])){ 
       $sql  .= $virgula." c83_anousu = $this->c83_anousu ";
       $virgula = ",";
       if(trim($this->c83_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c83_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c83_codigo!=null){
       $sql .= " c83_codigo = $this->c83_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c83_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7264,'$this->c83_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_codigo"]) || $this->c83_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1204,7264,'".AddSlashes(pg_result($resaco,$conresaco,'c83_codigo'))."','$this->c83_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_codrel"]) || $this->c83_codrel != "")
           $resac = db_query("insert into db_acount values($acount,1204,7265,'".AddSlashes(pg_result($resaco,$conresaco,'c83_codrel'))."','$this->c83_codrel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_variavel"]) || $this->c83_variavel != "")
           $resac = db_query("insert into db_acount values($acount,1204,7266,'".AddSlashes(pg_result($resaco,$conresaco,'c83_variavel'))."','$this->c83_variavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_anousu"]) || $this->c83_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1204,8640,'".AddSlashes(pg_result($resaco,$conresaco,'c83_anousu'))."','$this->c83_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c83 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c83 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c83_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c83_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7264,'$c83_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1204,7264,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1204,7265,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_codrel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1204,7266,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_variavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1204,8640,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conrelinfo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c83_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c83_codigo = $c83_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c83 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c83_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c83 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c83_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c83_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:conrelinfo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c83_codigo=null,$instit="1", $campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conrelinfo ";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = conrelinfo.c83_codrel";
     $sql .= "      left join conrelvalor  on  conrelvalor.c83_codigo = conrelinfo.c83_codigo and conrelvalor.c83_instit=$instit";
     $sql2 = "";
     if($dbwhere==""){
       if($c83_codigo!=null ){
         $sql2 .= " where conrelinfo.c83_codigo = $c83_codigo "; 
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
   function sql_query_file ( $c83_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conrelinfo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c83_codigo!=null ){
         $sql2 .= " where conrelinfo.c83_codigo = $c83_codigo "; 
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
   function getValorVariavel($c83_codigo, $instit="1",$periodo=null) {
  
  $instit = str_replace("-", ",", $instit);
  $sSql   = "select conrelinfo.c83_codigo,";
  $sSql  .= "       sum( cast( regexp_replace( coalesce(nullif(trim(conrelvalor.c83_informacao),''), '0') , '[^0-9.,-]' , '', 'g') as float8)) as c83_informacao ";
  $sSql  .= "from conrelinfo";
  $sSql  .= "      inner join orcparamrel on o42_codparrel = conrelinfo.c83_codrel";
  $sSql  .= "      left join conrelvalor on conrelvalor.c83_codigo = conrelinfo.c83_codigo  and c83_instit in ( $instit ) ";
  $sSql  .= "where conrelinfo.c83_codigo = $c83_codigo ";
  
  if ($periodo != null){
    $sSql .= "and conrelvalor.c83_periodo = '$periodo' ";
  }

  $sSql .= "group by conrelinfo.c83_codigo ";
  $sSql .= "order by conrelinfo.c83_codigo";
  //echo "<br>$sSql";
  $rsValor = $this->sql_record($sSql);
  $sValor  = 0;
  if ($this->numrows > 0) {
    $sValor = pg_result($rsValor, 0, "c83_informacao");
  }
  return $sValor;
}
   function sql_query_valores ( $c83_codigo,$instit="1",$periodo=null){
  $sql  = "select conrelinfo.c83_codigo,";
  $sql .= "       sum( cast( regexp_replace( coalesce(nullif(trim(conrelvalor.c83_informacao),''), '0') , '[^0-9.,-]' , '', 'g') as float8)) as c83_informacao ";
  $sql .= "from conrelinfo";
  $sql .= "      inner join orcparamrel on o42_codparrel = conrelinfo.c83_codrel";
  $sql .= "      left join conrelvalor on conrelvalor.c83_codigo = conrelinfo.c83_codigo  and c83_instit in ( $instit ) ";
  $sql .= "where o42_codparrel = $c83_codigo ";
  
  if ($periodo != null){
    $sql .= "and conrelvalor.c83_periodo = '$periodo' ";
  }

  $sql .= "group by conrelinfo.c83_codigo ";
  $sql .= "order by c83_codigo";

  return $sql;
}
   function sql_query_valores2( $c83_codigo,$instit="1",$periodo=null){
  $sql  = "select conrelinfo.c83_codigo,";
  $sql .= "       cast( regexp_replace( coalesce(nullif(trim(conrelvalor.c83_informacao),''), '0') , '[^0-9.,-]' , '', 'g') as float8) as c83_informacao, ";
  $sql .= "       c83_periodo ";
  $sql .= "from conrelinfo";
  $sql .= "      inner join orcparamrel on o42_codparrel = conrelinfo.c83_codrel";
  $sql .= "      left join conrelvalor on conrelvalor.c83_codigo = conrelinfo.c83_codigo  and c83_instit in ( $instit ) ";
  $sql .= "where o42_codparrel = $c83_codigo ";
  
  if ($periodo != null){
    $sql .= "and conrelvalor.c83_periodo in ($periodo) ";
  }

  $sql .= "order by c83_codigo";

  return $sql;
}
}
?>