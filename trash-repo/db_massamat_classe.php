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
//CLASSE DA ENTIDADE massamat
class cl_massamat { 
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
   var $j59_codigo = 0; 
   var $j59_matric = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j59_codigo = int4 = C�digo da sequencia 
                 j59_matric = int4 = C�digo da massa falida 
                 ";
   //funcao construtor da classe 
   function cl_massamat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("massamat"); 
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
       $this->j59_codigo = ($this->j59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j59_codigo"]:$this->j59_codigo);
       $this->j59_matric = ($this->j59_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j59_matric"]:$this->j59_matric);
     }else{
       $this->j59_codigo = ($this->j59_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j59_codigo"]:$this->j59_codigo);
       $this->j59_matric = ($this->j59_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j59_matric"]:$this->j59_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j59_codigo,$j59_matric){ 
      $this->atualizacampos();
       $this->j59_codigo = $j59_codigo; 
       $this->j59_matric = $j59_matric; 
     if(($this->j59_codigo == null) || ($this->j59_codigo == "") ){ 
       $this->erro_sql = " Campo j59_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j59_matric == null) || ($this->j59_matric == "") ){ 
       $this->erro_sql = " Campo j59_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into massamat(
                                       j59_codigo 
                                      ,j59_matric 
                       )
                values (
                                $this->j59_codigo 
                               ,$this->j59_matric 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela com massas falidas ($this->j59_codigo."-".$this->j59_matric) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela com massas falidas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela com massas falidas ($this->j59_codigo."-".$this->j59_matric) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->j59_codigo,$this->j59_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2538,'$this->j59_codigo','I')");
       $resac = pg_query("insert into db_acountkey values($acount,2539,'$this->j59_matric','I')");
       $resac = pg_query("insert into db_acount values($acount,415,2538,'','".pg_result($resaco,0,'j59_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,415,2539,'','".pg_result($resaco,0,'j59_matric')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j59_codigo=null,$j59_matric=null) { 
      $this->atualizacampos();
     $sql = " update massamat set ";
     $virgula = "";
     if($this->j59_codigo!="" || isset($GLOBALS["HTTP_POST_VARS"]["j59_codigo"])){ 
       $sql  .= $virgula." j59_codigo = $this->j59_codigo ";
       $virgula = ",";
       if($this->j59_codigo == null ){ 
         $this->erro_sql = " Campo C�digo da sequencia nao Informado.";
         $this->erro_campo = "j59_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if($this->j59_matric!="" || isset($GLOBALS["HTTP_POST_VARS"]["j59_matric"])){ 
       $sql  .= $virgula." j59_matric = $this->j59_matric ";
       $virgula = ",";
       if($this->j59_matric == null ){ 
         $this->erro_sql = " Campo C�digo da massa falida nao Informado.";
         $this->erro_campo = "j59_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  j59_codigo = $this->j59_codigo
 and  j59_matric = $this->j59_matric
";
     $resaco = $this->sql_record($this->sql_query_file($this->j59_codigo,$this->j59_matric));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2538,'$this->j59_codigo','A')");
       $resac = pg_query("insert into db_acountkey values($acount,2539,'$this->j59_matric','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j59_codigo"]))
         $resac = pg_query("insert into db_acount values($acount,415,2538,'".pg_result($resaco,0,'j59_codigo')."','$this->j59_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["j59_matric"]))
         $resac = pg_query("insert into db_acount values($acount,415,2539,'".pg_result($resaco,0,'j59_matric')."','$this->j59_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com massas falidas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com massas falidas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j59_codigo=null,$j59_matric=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->j59_codigo,$this->j59_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,2538,'$this->j59_codigo','E')");
       $resac = pg_query("insert into db_acountkey values($acount,2539,'$this->j59_matric','E')");
       $resac = pg_query("insert into db_acount values($acount,415,2538,'','".pg_result($resaco,0,'j59_codigo')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,415,2539,'','".pg_result($resaco,0,'j59_matric')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from massamat
                    where ";
     $sql2 = "";
      if($this->j59_codigo != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " j59_codigo = $this->j59_codigo ";
}
      if($this->j59_matric != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " j59_matric = $this->j59_matric ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela com massas falidas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela com massas falidas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j59_codigo."-".$this->j59_matric;
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
   function sql_query ( $j59_codigo=null,$j59_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from massamat ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = massamat.j59_matric";
     $sql .= "      inner join massafalida  on  massafalida.j58_codigo = massamat.j59_codigo";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = massafalida.j58_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($j59_codigo!=null ){
         $sql2 .= " where massamat.j59_codigo = $j59_codigo "; 
       } 
       if($j59_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " massamat.j59_matric = $j59_matric "; 
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
   function sql_query_file ( $j59_codigo=null,$j59_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from massamat ";
     $sql2 = "";
     if($dbwhere==""){
       if($j59_codigo!=null ){
         $sql2 .= " where massamat.j59_codigo = $j59_codigo "; 
       } 
       if($j59_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " massamat.j59_matric = $j59_matric "; 
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