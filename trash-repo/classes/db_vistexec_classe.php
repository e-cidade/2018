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

//MODULO: fiscal
//CLASSE DA ENTIDADE vistexec
class cl_vistexec { 
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
   var $y11_codvist = 0; 
   var $y11_codigo = 0; 
   var $y11_codi = 0; 
   var $y11_numero = 0; 
   var $y11_compl = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y11_codvist = int4 = Código da Vistoria 
                 y11_codigo = int4 = Logradouro 
                 y11_codi = int4 = Bairro 
                 y11_numero = int4 = Número 
                 y11_compl = varchar(10) = Complemento 
                 ";
   //funcao construtor da classe 
   function cl_vistexec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vistexec"); 
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
       $this->y11_codvist = ($this->y11_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_codvist"]:$this->y11_codvist);
       $this->y11_codigo = ($this->y11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_codigo"]:$this->y11_codigo);
       $this->y11_codi = ($this->y11_codi == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_codi"]:$this->y11_codi);
       $this->y11_numero = ($this->y11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_numero"]:$this->y11_numero);
       $this->y11_compl = ($this->y11_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_compl"]:$this->y11_compl);
     }else{
       $this->y11_codvist = ($this->y11_codvist == ""?@$GLOBALS["HTTP_POST_VARS"]["y11_codvist"]:$this->y11_codvist);
     }
   }
   // funcao para inclusao
   function incluir ($y11_codvist){ 
      $this->atualizacampos();
     if($this->y11_codigo == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "y11_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y11_codi == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "y11_codi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y11_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "y11_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->y11_codvist = $y11_codvist; 
     if(($this->y11_codvist == null) || ($this->y11_codvist == "") ){ 
       $this->erro_sql = " Campo y11_codvist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vistexec(
                                       y11_codvist 
                                      ,y11_codigo 
                                      ,y11_codi 
                                      ,y11_numero 
                                      ,y11_compl 
                       )
                values (
                                $this->y11_codvist 
                               ,$this->y11_codigo 
                               ,$this->y11_codi 
                               ,$this->y11_numero 
                               ,'$this->y11_compl' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "local da execução da vistoria ($this->y11_codvist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "local da execução da vistoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "local da execução da vistoria ($this->y11_codvist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y11_codvist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y11_codvist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5108,'$this->y11_codvist','I')");
       $resac = db_query("insert into db_acount values($acount,728,5108,'','".AddSlashes(pg_result($resaco,0,'y11_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,728,5122,'','".AddSlashes(pg_result($resaco,0,'y11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,728,5111,'','".AddSlashes(pg_result($resaco,0,'y11_codi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,728,5109,'','".AddSlashes(pg_result($resaco,0,'y11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,728,5110,'','".AddSlashes(pg_result($resaco,0,'y11_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y11_codvist=null) { 
      $this->atualizacampos();
     $sql = " update vistexec set ";
     $virgula = "";
     if(trim($this->y11_codvist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y11_codvist"])){ 
       $sql  .= $virgula." y11_codvist = $this->y11_codvist ";
       $virgula = ",";
       if(trim($this->y11_codvist) == null ){ 
         $this->erro_sql = " Campo Código da Vistoria nao Informado.";
         $this->erro_campo = "y11_codvist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y11_codigo"])){ 
       $sql  .= $virgula." y11_codigo = $this->y11_codigo ";
       $virgula = ",";
       if(trim($this->y11_codigo) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "y11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y11_codi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y11_codi"])){ 
       $sql  .= $virgula." y11_codi = $this->y11_codi ";
       $virgula = ",";
       if(trim($this->y11_codi) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "y11_codi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y11_numero"])){ 
       $sql  .= $virgula." y11_numero = $this->y11_numero ";
       $virgula = ",";
       if(trim($this->y11_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "y11_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y11_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y11_compl"])){ 
       $sql  .= $virgula." y11_compl = '$this->y11_compl' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($y11_codvist!=null){
       $sql .= " y11_codvist = $this->y11_codvist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y11_codvist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5108,'$this->y11_codvist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y11_codvist"]))
           $resac = db_query("insert into db_acount values($acount,728,5108,'".AddSlashes(pg_result($resaco,$conresaco,'y11_codvist'))."','$this->y11_codvist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y11_codigo"]))
           $resac = db_query("insert into db_acount values($acount,728,5122,'".AddSlashes(pg_result($resaco,$conresaco,'y11_codigo'))."','$this->y11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y11_codi"]))
           $resac = db_query("insert into db_acount values($acount,728,5111,'".AddSlashes(pg_result($resaco,$conresaco,'y11_codi'))."','$this->y11_codi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y11_numero"]))
           $resac = db_query("insert into db_acount values($acount,728,5109,'".AddSlashes(pg_result($resaco,$conresaco,'y11_numero'))."','$this->y11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y11_compl"]))
           $resac = db_query("insert into db_acount values($acount,728,5110,'".AddSlashes(pg_result($resaco,$conresaco,'y11_compl'))."','$this->y11_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "local da execução da vistoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y11_codvist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "local da execução da vistoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y11_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y11_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y11_codvist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y11_codvist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5108,'$y11_codvist','E')");
         $resac = db_query("insert into db_acount values($acount,728,5108,'','".AddSlashes(pg_result($resaco,$iresaco,'y11_codvist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,728,5122,'','".AddSlashes(pg_result($resaco,$iresaco,'y11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,728,5111,'','".AddSlashes(pg_result($resaco,$iresaco,'y11_codi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,728,5109,'','".AddSlashes(pg_result($resaco,$iresaco,'y11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,728,5110,'','".AddSlashes(pg_result($resaco,$iresaco,'y11_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vistexec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y11_codvist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y11_codvist = $y11_codvist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "local da execução da vistoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y11_codvist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "local da execução da vistoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y11_codvist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y11_codvist;
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
        $this->erro_sql   = "Record Vazio na Tabela:vistexec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y11_codvist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistexec ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = vistexec.y11_codi";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = vistexec.y11_codigo";
     $sql .= "      inner join vistorias  on  vistorias.y70_codvist = vistexec.y11_codvist";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vistorias.y70_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vistorias.y70_coddepto";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = vistorias.y70_ultandam";
     $sql .= "      inner join tipovistorias  on  tipovistorias.y77_codtipo = vistorias.y70_tipovist";
     $sql2 = "";
     if($dbwhere==""){
       if($y11_codvist!=null ){
         $sql2 .= " where vistexec.y11_codvist = $y11_codvist "; 
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
   function sql_query_file ( $y11_codvist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vistexec ";
     $sql2 = "";
     if($dbwhere==""){
       if($y11_codvist!=null ){
         $sql2 .= " where vistexec.y11_codvist = $y11_codvist "; 
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