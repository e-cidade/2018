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
//CLASSE DA ENTIDADE massafalida
class cl_massafalida { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $j58_codigo = 0; 
   var $j58_data_dia = null; 
   var $j58_data_mes = null; 
   var $j58_data_ano = null; 
   var $j58_data = null; 
   var $j58_numcgm = 0; 
   var $j58_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j58_codigo = int4 = C�digo de lan�amento 
                 j58_data = date =  
                 j58_numcgm = int4 = NUMCGM do respons�vel 
                 j58_obs = text = Observa��o da massa falida 
                 ";
   //funcao construtor da classe 
   function cl_massafalida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("massafalida"); 
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
       $this->j58_codigo = ($this->j58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j58_codigo"]:$this->j58_codigo);
       if($this->j58_data == ""){
         $this->j58_data_dia = @$GLOBALS["HTTP_POST_VARS"]["j58_data_dia"];
         $this->j58_data_mes = @$GLOBALS["HTTP_POST_VARS"]["j58_data_mes"];
         $this->j58_data_ano = @$GLOBALS["HTTP_POST_VARS"]["j58_data_ano"];
         if($this->j58_data_dia != ""){
            $this->j58_data = $this->j58_data_ano."-".$this->j58_data_mes."-".$this->j58_data_dia;
         }
       }
       $this->j58_numcgm = ($this->j58_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["j58_numcgm"]:$this->j58_numcgm);
       $this->j58_obs = ($this->j58_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j58_obs"]:$this->j58_obs);
     }else{
       $this->j58_codigo = ($this->j58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j58_codigo"]:$this->j58_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j58_codigo){ 
      $this->atualizacampos();
     if($this->j58_data == null ){ 
       $this->erro_sql = " Campo  nao Informado.";
       $this->erro_campo = "j58_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j58_numcgm == null ){ 
       $this->erro_sql = " Campo NUMCGM do respons�vel nao Informado.";
       $this->erro_campo = "j58_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j58_obs == null ){ 
       $this->erro_sql = " Campo Observa��o da massa falida nao Informado.";
       $this->erro_campo = "j58_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j58_codigo == "" || $j58_codigo == null ){
       $result = @pg_query("select nextval('massafalida_j58_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: massafalida_j58_codigo_seq do campo: j58_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j58_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from massafalida_j58_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j58_codigo)){
         $this->erro_sql = " Campo j58_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j58_codigo = $j58_codigo; 
       }
     }
     if(($this->j58_codigo == null) || ($this->j58_codigo == "") ){ 
       $this->erro_sql = " Campo j58_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into massafalida(
                                       j58_codigo 
                                      ,j58_data 
                                      ,j58_numcgm 
                                      ,j58_obs 
                       )
                values (
                                $this->j58_codigo 
                               ,".($this->j58_data == "null" || $this->j58_data == ""?"null":"'".$this->j58_data."'")." 
                               ,$this->j58_numcgm 
                               ,'$this->j58_obs' 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de massas falidas ($this->j58_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de massas falidas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de massas falidas ($this->j58_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->j58_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2534,'$this->j58_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,414,2534,'','".pg_result($resaco,0,'j58_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2536,'','".pg_result($resaco,0,'j58_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2535,'','".pg_result($resaco,0,'j58_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2537,'','".pg_result($resaco,0,'j58_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j58_codigo=null) { 
      $this->atualizacampos();
     $sql = " update massafalida set ";
     $virgula = "";
     if($this->j58_codigo!="" || isset($GLOBALS["HTTP_POST_VARS"]["j58_codigo"])){ 
       $sql  .= $virgula." j58_codigo = $this->j58_codigo ";
       $virgula = ",";
       if($this->j58_codigo == null ){ 
         $this->erro_sql = " Campo C�digo de lan�amento nao Informado.";
         $this->erro_campo = "j58_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->j58_data!="" || isset($GLOBALS["HTTP_POST_VARS"]["j58_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j58_data_dia"] !="") ){ 
       $sql  .= $virgula." j58_data = '$this->j58_data' ";
       $virgula = ",";
       if($this->j58_data == null ){ 
         $this->erro_sql = " Campo  nao Informado.";
         $this->erro_campo = "j58_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if($this->j58_data!="" || isset($GLOBALS["HTTP_POST_VARS"]["j58_data"])){ 
         $sql  .= $virgula." j58_data = null ";
         $virgula = ",";
         if($this->j58_data == null ){ 
           $this->erro_sql = " Campo  nao Informado.";
           $this->erro_campo = "j58_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if($this->j58_numcgm!="" || isset($GLOBALS["HTTP_POST_VARS"]["j58_numcgm"])){ 
       $sql  .= $virgula." j58_numcgm = $this->j58_numcgm ";
       $virgula = ",";
       if($this->j58_numcgm == null ){ 
         $this->erro_sql = " Campo NUMCGM do respons�vel nao Informado.";
         $this->erro_campo = "j58_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->j58_obs!="" || isset($GLOBALS["HTTP_POST_VARS"]["j58_obs"])){ 
       $sql  .= $virgula." j58_obs = '$this->j58_obs' ";
       $virgula = ",";
       if($this->j58_obs == null ){ 
         $this->erro_sql = " Campo Observa��o da massa falida nao Informado.";
         $this->erro_campo = "j58_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  j58_codigo = $this->j58_codigo
";
     $resaco = $this->sql_record($this->sql_query_file($this->j58_codigo));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2534,'$this->j58_codigo','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j58_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,414,2534,'".pg_result($resaco,0,'j58_codigo')."','$this->j58_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j58_data"]))
         $resac = pg_query("insert into db_acount values($acount,414,2536,'".pg_result($resaco,0,'j58_data')."','$this->j58_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j58_numcgm"]))
         $resac = pg_query("insert into db_acount values($acount,414,2535,'".pg_result($resaco,0,'j58_numcgm')."','$this->j58_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j58_obs"]))
         $resac = pg_query("insert into db_acount values($acount,414,2537,'".pg_result($resaco,0,'j58_obs')."','$this->j58_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de massas falidas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de massas falidas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j58_codigo=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->j58_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2534,'$this->j58_codigo','E')");
       $resac = pg_query("insert into db_acount values($acount,414,2534,'','".pg_result($resaco,0,'j58_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2536,'','".pg_result($resaco,0,'j58_data')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2535,'','".pg_result($resaco,0,'j58_numcgm')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,414,2537,'','".pg_result($resaco,0,'j58_obs')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from massafalida
                    where ";
     $sql2 = "";
      if($this->j58_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " j58_codigo = $this->j58_codigo ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de massas falidas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->j58_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de massas falidas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j58_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from massafalida ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = massafalida.j58_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j58_codigo!=null ){
         $sql2 .= " where massafalida.j58_codigo = $j58_codigo "; 
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
   function sql_query_file ( $j58_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from massafalida ";
     $sql2 = "";
     if($dbwhere==""){
       if($j58_codigo!=null ){
         $sql2 .= " where massafalida.j58_codigo = $j58_codigo "; 
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