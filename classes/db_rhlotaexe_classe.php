<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhlotaexe
class cl_rhlotaexe { 
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
   var $rh26_anousu = 0; 
   var $rh26_codigo = 0; 
   var $rh26_orgao = 0; 
   var $rh26_unidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh26_anousu = int4 = Exercício 
                 rh26_codigo = int4 = Código da Lotação 
                 rh26_orgao = int4 = Órgão 
                 rh26_unidade = int4 = Unidade 
                 ";
   //funcao construtor da classe 
   function cl_rhlotaexe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlotaexe"); 
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
       $this->rh26_anousu = ($this->rh26_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_anousu"]:$this->rh26_anousu);
       $this->rh26_codigo = ($this->rh26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_codigo"]:$this->rh26_codigo);
       $this->rh26_orgao = ($this->rh26_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_orgao"]:$this->rh26_orgao);
       $this->rh26_unidade = ($this->rh26_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_unidade"]:$this->rh26_unidade);
     }else{
       $this->rh26_anousu = ($this->rh26_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_anousu"]:$this->rh26_anousu);
       $this->rh26_codigo = ($this->rh26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh26_codigo"]:$this->rh26_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh26_anousu,$rh26_codigo){ 
      $this->atualizacampos();
     if($this->rh26_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "rh26_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh26_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "rh26_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh26_anousu = $rh26_anousu; 
       $this->rh26_codigo = $rh26_codigo; 
     if(($this->rh26_anousu == null) || ($this->rh26_anousu == "") ){ 
       $this->erro_sql = " Campo rh26_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh26_codigo == null) || ($this->rh26_codigo == "") ){ 
       $this->erro_sql = " Campo rh26_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotaexe(
                                       rh26_anousu 
                                      ,rh26_codigo 
                                      ,rh26_orgao 
                                      ,rh26_unidade 
                       )
                values (
                                $this->rh26_anousu 
                               ,$this->rh26_codigo 
                               ,$this->rh26_orgao 
                               ,$this->rh26_unidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lotações analíticas ($this->rh26_anousu."-".$this->rh26_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lotações analíticas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lotações analíticas ($this->rh26_anousu."-".$this->rh26_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh26_anousu."-".$this->rh26_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh26_anousu,$this->rh26_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7130,'$this->rh26_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,7131,'$this->rh26_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1181,7130,'','".AddSlashes(pg_result($resaco,0,'rh26_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1181,7131,'','".AddSlashes(pg_result($resaco,0,'rh26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1181,7132,'','".AddSlashes(pg_result($resaco,0,'rh26_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1181,7133,'','".AddSlashes(pg_result($resaco,0,'rh26_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh26_anousu=null,$rh26_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhlotaexe set ";
     $virgula = "";
     if(trim($this->rh26_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh26_anousu"])){ 
       $sql  .= $virgula." rh26_anousu = $this->rh26_anousu ";
       $virgula = ",";
       if(trim($this->rh26_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh26_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh26_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh26_codigo"])){ 
       $sql  .= $virgula." rh26_codigo = $this->rh26_codigo ";
       $virgula = ",";
       if(trim($this->rh26_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Lotação nao Informado.";
         $this->erro_campo = "rh26_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh26_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh26_orgao"])){ 
       $sql  .= $virgula." rh26_orgao = $this->rh26_orgao ";
       $virgula = ",";
       if(trim($this->rh26_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "rh26_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh26_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh26_unidade"])){ 
       $sql  .= $virgula." rh26_unidade = $this->rh26_unidade ";
       $virgula = ",";
       if(trim($this->rh26_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "rh26_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh26_anousu!=null){
       $sql .= " rh26_anousu = $this->rh26_anousu";
     }
     if($rh26_codigo!=null){
       $sql .= " and  rh26_codigo = $this->rh26_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh26_anousu,$this->rh26_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7130,'$this->rh26_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,7131,'$this->rh26_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh26_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1181,7130,'".AddSlashes(pg_result($resaco,$conresaco,'rh26_anousu'))."','$this->rh26_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh26_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1181,7131,'".AddSlashes(pg_result($resaco,$conresaco,'rh26_codigo'))."','$this->rh26_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh26_orgao"]))
           $resac = db_query("insert into db_acount values($acount,1181,7132,'".AddSlashes(pg_result($resaco,$conresaco,'rh26_orgao'))."','$this->rh26_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh26_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1181,7133,'".AddSlashes(pg_result($resaco,$conresaco,'rh26_unidade'))."','$this->rh26_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotações analíticas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh26_anousu."-".$this->rh26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotações analíticas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh26_anousu."-".$this->rh26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh26_anousu."-".$this->rh26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh26_anousu=null,$rh26_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh26_anousu,$rh26_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7130,'$rh26_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,7131,'$rh26_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1181,7130,'','".AddSlashes(pg_result($resaco,$iresaco,'rh26_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1181,7131,'','".AddSlashes(pg_result($resaco,$iresaco,'rh26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1181,7132,'','".AddSlashes(pg_result($resaco,$iresaco,'rh26_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1181,7133,'','".AddSlashes(pg_result($resaco,$iresaco,'rh26_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlotaexe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh26_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh26_anousu = $rh26_anousu ";
        }
        if($rh26_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh26_codigo = $rh26_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotações analíticas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh26_anousu."-".$rh26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lotações analíticas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh26_anousu."-".$rh26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh26_anousu."-".$rh26_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotaexe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh26_anousu=null,$rh26_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotaexe ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu  = rhlotaexe.rh26_anousu 
                                          and  orcunidade.o41_orgao   = rhlotaexe.rh26_orgao 
                                          and  orcunidade.o41_unidade = rhlotaexe.rh26_unidade";
     $sql .= "      inner join orcorgao    on  orcorgao.o40_anousu    = orcunidade.o41_anousu 
                                          and  orcorgao.o40_orgao     = orcunidade.o41_orgao   ";
     /*
     $sql .= "      inner join orcorgao  as a on   a.o40_anousu = orcunidade.o41_anousu and   a.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join orcorgao  as b on   b.o40_anousu = orcunidade.o41_anousu and   b.o40_orgao = orcunidade.o41_orgao";
     */
     $sql2 = "";
     if($dbwhere==""){
       if($rh26_anousu!=null ){
         $sql2 .= " where rhlotaexe.rh26_anousu = $rh26_anousu "; 
       } 
       if($rh26_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotaexe.rh26_codigo = $rh26_codigo "; 
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
   function sql_query_file ( $rh26_anousu=null,$rh26_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlotaexe ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh26_anousu!=null ){
         $sql2 .= " where rhlotaexe.rh26_anousu = $rh26_anousu "; 
       } 
       if($rh26_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhlotaexe.rh26_codigo = $rh26_codigo "; 
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

  function sql_query_servidores( $iAnoUsu, $iMesUsu, $sCampos = null, $sWhere, $iInstituicao = null ) {

  	if ( empty($sCampos) ) {
  		$sCampos = "*";
  	}
  	 
  	if ( empty($iInstituicao) ) {
			$iInstituicao = db_getsession('DB_instit');
  	}
  	 
  	$sSql = "select $sCampos                                           \n";
  	$sSql.= "  from rhpessoalmov                                       \n";
  	$sSql.= "       inner join rhlota     on r70_codigo  = rh02_lota   \n";
  	$sSql.= "       inner join rhlotaexe  on rh26_codigo = r70_codigo  \n";
  	$sSql.= "                            and rh26_anousu = rh02_anousu \n";
  	$sSql.= "       left  join orcorgao   on o40_orgao   = rh26_orgao  \n";
  	$sSql.= "                            and o40_anousu  = rh02_anousu \n";
  	$sSql.= "                            and o40_instit  = rh02_instit \n";
  	$sSql.= " where rh02_anousu = $iAnoUsu                             \n";
  	$sSql.= "   and rh02_mesusu = $iMesUsu                             \n";
  	$sSql.= "   and rh02_instit = {$iInstituicao}											 \n";

  	if ( !empty($sWhere) ) {
  		$sSql .= " and $sWhere";
  	}

  	return $sSql;
  }
}
?>