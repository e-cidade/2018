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

//MODULO: contrib
//CLASSE DA ENTIDADE parcontrib
class cl_parcontrib { 
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
   var $d12_receita = 0; 
   var $d12_numtot = 0; 
   var $d12_perunica = 0; 
   var $d12_hist = 0; 
   var $d12_notitipo = 0; 
   var $d12_tipo = 0; 
   var $d12_perc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d12_receita = int4 = codigo da receita 
                 d12_numtot = int4 = Total de parcelas 
                 d12_perunica = float8 = Pecentual desconto única 
                 d12_hist = int4 = Hist.Calc. 
                 d12_notitipo = int4 = Procedência 
                 d12_tipo = int4 = tipo de debito 
                 d12_perc = float8 = Percentual 
                 ";
   //funcao construtor da classe 
   function cl_parcontrib() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parcontrib"); 
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
       $this->d12_receita = ($this->d12_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_receita"]:$this->d12_receita);
       $this->d12_numtot = ($this->d12_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_numtot"]:$this->d12_numtot);
       $this->d12_perunica = ($this->d12_perunica == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_perunica"]:$this->d12_perunica);
       $this->d12_hist = ($this->d12_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_hist"]:$this->d12_hist);
       $this->d12_notitipo = ($this->d12_notitipo == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_notitipo"]:$this->d12_notitipo);
       $this->d12_tipo = ($this->d12_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_tipo"]:$this->d12_tipo);
       $this->d12_perc = ($this->d12_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["d12_perc"]:$this->d12_perc);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->d12_receita == null ){ 
       $this->erro_sql = " Campo codigo da receita nao Informado.";
       $this->erro_campo = "d12_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d12_numtot == null ){ 
       $this->erro_sql = " Campo Total de parcelas nao Informado.";
       $this->erro_campo = "d12_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d12_perunica == null ){ 
       $this->d12_perunica = "0";
     }
     if($this->d12_hist == null ){ 
       $this->erro_sql = " Campo Hist.Calc. nao Informado.";
       $this->erro_campo = "d12_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d12_notitipo == null ){ 
       $this->erro_sql = " Campo Procedência nao Informado.";
       $this->erro_campo = "d12_notitipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d12_tipo == null ){ 
       $this->erro_sql = " Campo tipo de debito nao Informado.";
       $this->erro_campo = "d12_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d12_perc == null ){ 
       $this->d12_perc = "0";
     }
     $sql = "insert into parcontrib(
                                       d12_receita 
                                      ,d12_numtot 
                                      ,d12_perunica 
                                      ,d12_hist 
                                      ,d12_notitipo 
                                      ,d12_tipo 
                                      ,d12_perc 
                       )
                values (
                                $this->d12_receita 
                               ,$this->d12_numtot 
                               ,$this->d12_perunica 
                               ,$this->d12_hist 
                               ,$this->d12_notitipo 
                               ,$this->d12_tipo 
                               ,$this->d12_perc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros da contribuição () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros da contribuição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros da contribuição () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update parcontrib set ";
     $virgula = "";
     if(trim($this->d12_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_receita"])){ 
        if(trim($this->d12_receita)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_receita"])){ 
           $this->d12_receita = "0" ; 
        } 
       $sql  .= $virgula." d12_receita = $this->d12_receita ";
       $virgula = ",";
       if(trim($this->d12_receita) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "d12_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d12_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_numtot"])){ 
        if(trim($this->d12_numtot)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_numtot"])){ 
           $this->d12_numtot = "0" ; 
        } 
       $sql  .= $virgula." d12_numtot = $this->d12_numtot ";
       $virgula = ",";
       if(trim($this->d12_numtot) == null ){ 
         $this->erro_sql = " Campo Total de parcelas nao Informado.";
         $this->erro_campo = "d12_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d12_perunica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_perunica"])){ 
        if(trim($this->d12_perunica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_perunica"])){ 
           $this->d12_perunica = "0" ; 
        } 
       $sql  .= $virgula." d12_perunica = $this->d12_perunica ";
       $virgula = ",";
     }
     if(trim($this->d12_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_hist"])){ 
        if(trim($this->d12_hist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_hist"])){ 
           $this->d12_hist = "0" ; 
        } 
       $sql  .= $virgula." d12_hist = $this->d12_hist ";
       $virgula = ",";
       if(trim($this->d12_hist) == null ){ 
         $this->erro_sql = " Campo Hist.Calc. nao Informado.";
         $this->erro_campo = "d12_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d12_notitipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_notitipo"])){ 
        if(trim($this->d12_notitipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_notitipo"])){ 
           $this->d12_notitipo = "0" ; 
        } 
       $sql  .= $virgula." d12_notitipo = $this->d12_notitipo ";
       $virgula = ",";
       if(trim($this->d12_notitipo) == null ){ 
         $this->erro_sql = " Campo Procedência nao Informado.";
         $this->erro_campo = "d12_notitipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d12_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_tipo"])){ 
        if(trim($this->d12_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_tipo"])){ 
           $this->d12_tipo = "0" ; 
        } 
       $sql  .= $virgula." d12_tipo = $this->d12_tipo ";
       $virgula = ",";
       if(trim($this->d12_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "d12_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d12_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d12_perc"])){ 
        if(trim($this->d12_perc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d12_perc"])){ 
           $this->d12_perc = "0" ; 
        } 
       $sql  .= $virgula." d12_perc = $this->d12_perc ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     
$result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da contribuição nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da contribuição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from parcontrib
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da contribuição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da contribuição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:parcontrib";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="parcontrib.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parcontrib ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = parcontrib.d12_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = parcontrib.d12_receita";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = parcontrib.d12_tipo";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = parcontrib.d12_notitipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where parcontrib.oid = $oid";
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parcontrib ";
     $sql2 = "";
     if($dbwhere==""){
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