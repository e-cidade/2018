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
//CLASSE DA ENTIDADE iptucalclogmat
class cl_iptucalclogmat { 
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
   var $j28_codigo = 0; 
   var $j28_matric = 0; 
   var $j28_tipologcalc = 0; 
   var $j28_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j28_codigo = int4 = Sequencial 
                 j28_matric = int4 = Matrícula do Imóvel 
                 j28_tipologcalc = int4 = Codigo 
                 j28_obs = text = Observacoes 
                 ";
   //funcao construtor da classe 
   function cl_iptucalclogmat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalclogmat"); 
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
       $this->j28_codigo = ($this->j28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_codigo"]:$this->j28_codigo);
       $this->j28_matric = ($this->j28_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_matric"]:$this->j28_matric);
       $this->j28_tipologcalc = ($this->j28_tipologcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_tipologcalc"]:$this->j28_tipologcalc);
       $this->j28_obs = ($this->j28_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_obs"]:$this->j28_obs);
     }else{
       $this->j28_codigo = ($this->j28_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_codigo"]:$this->j28_codigo);
       $this->j28_matric = ($this->j28_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j28_matric"]:$this->j28_matric);
     }
   }
   // funcao para inclusao
   function incluir ($j28_codigo,$j28_matric){ 
      $this->atualizacampos();
     if($this->j28_tipologcalc == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "j28_tipologcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j28_obs == null ){ 
       $this->erro_sql = " Campo Observacoes nao Informado.";
       $this->erro_campo = "j28_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j28_codigo = $j28_codigo; 
       $this->j28_matric = $j28_matric; 
     if(($this->j28_codigo == null) || ($this->j28_codigo == "") ){ 
       $this->erro_sql = " Campo j28_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j28_matric == null) || ($this->j28_matric == "") ){ 
       $this->erro_sql = " Campo j28_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalclogmat(
                                       j28_codigo 
                                      ,j28_matric 
                                      ,j28_tipologcalc 
                                      ,j28_obs 
                       )
                values (
                                $this->j28_codigo 
                               ,$this->j28_matric 
                               ,$this->j28_tipologcalc 
                               ,'$this->j28_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matriculas do log do calculo do iptu ($this->j28_codigo."-".$this->j28_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matriculas do log do calculo do iptu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matriculas do log do calculo do iptu ($this->j28_codigo."-".$this->j28_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j28_codigo."-".$this->j28_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j28_codigo,$this->j28_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7888,'$this->j28_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,7889,'$this->j28_matric','I')");
       $resac = db_query("insert into db_acount values($acount,1321,7888,'','".AddSlashes(pg_result($resaco,0,'j28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1321,7889,'','".AddSlashes(pg_result($resaco,0,'j28_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1321,7893,'','".AddSlashes(pg_result($resaco,0,'j28_tipologcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1321,9739,'','".AddSlashes(pg_result($resaco,0,'j28_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j28_codigo=null,$j28_matric=null) { 
      $this->atualizacampos();
     $sql = " update iptucalclogmat set ";
     $virgula = "";
     if(trim($this->j28_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j28_codigo"])){ 
       $sql  .= $virgula." j28_codigo = $this->j28_codigo ";
       $virgula = ",";
       if(trim($this->j28_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j28_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j28_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j28_matric"])){ 
       $sql  .= $virgula." j28_matric = $this->j28_matric ";
       $virgula = ",";
       if(trim($this->j28_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "j28_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j28_tipologcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j28_tipologcalc"])){ 
       $sql  .= $virgula." j28_tipologcalc = $this->j28_tipologcalc ";
       $virgula = ",";
       if(trim($this->j28_tipologcalc) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "j28_tipologcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j28_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j28_obs"])){ 
       $sql  .= $virgula." j28_obs = '$this->j28_obs' ";
       $virgula = ",";
       if(trim($this->j28_obs) == null ){ 
         $this->erro_sql = " Campo Observacoes nao Informado.";
         $this->erro_campo = "j28_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j28_codigo!=null){
       $sql .= " j28_codigo = $this->j28_codigo";
     }
     if($j28_matric!=null){
       $sql .= " and  j28_matric = $this->j28_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j28_codigo,$this->j28_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7888,'$this->j28_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,7889,'$this->j28_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j28_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1321,7888,'".AddSlashes(pg_result($resaco,$conresaco,'j28_codigo'))."','$this->j28_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j28_matric"]))
           $resac = db_query("insert into db_acount values($acount,1321,7889,'".AddSlashes(pg_result($resaco,$conresaco,'j28_matric'))."','$this->j28_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j28_tipologcalc"]))
           $resac = db_query("insert into db_acount values($acount,1321,7893,'".AddSlashes(pg_result($resaco,$conresaco,'j28_tipologcalc'))."','$this->j28_tipologcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j28_obs"]))
           $resac = db_query("insert into db_acount values($acount,1321,9739,'".AddSlashes(pg_result($resaco,$conresaco,'j28_obs'))."','$this->j28_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriculas do log do calculo do iptu nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j28_codigo."-".$this->j28_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matriculas do log do calculo do iptu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j28_codigo."-".$this->j28_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j28_codigo."-".$this->j28_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j28_codigo=null,$j28_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j28_codigo,$j28_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7888,'$j28_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,7889,'$j28_matric','E')");
         $resac = db_query("insert into db_acount values($acount,1321,7888,'','".AddSlashes(pg_result($resaco,$iresaco,'j28_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1321,7889,'','".AddSlashes(pg_result($resaco,$iresaco,'j28_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1321,7893,'','".AddSlashes(pg_result($resaco,$iresaco,'j28_tipologcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1321,9739,'','".AddSlashes(pg_result($resaco,$iresaco,'j28_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalclogmat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j28_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j28_codigo = $j28_codigo ";
        }
        if($j28_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j28_matric = $j28_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriculas do log do calculo do iptu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j28_codigo."-".$j28_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matriculas do log do calculo do iptu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j28_codigo."-".$j28_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j28_codigo."-".$j28_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalclogmat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j28_codigo=null,$j28_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalclogmat ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptucalclogmat.j28_matric";
     $sql .= "      inner join iptucalclog  on  iptucalclog.j27_codigo = iptucalclogmat.j28_codigo";
     $sql .= "      inner join iptucadlogcalc  on  iptucadlogcalc.j62_codigo = iptucalclogmat.j28_tipologcalc";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptucalclog.j27_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j28_codigo!=null ){
         $sql2 .= " where iptucalclogmat.j28_codigo = $j28_codigo "; 
       } 
       if($j28_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucalclogmat.j28_matric = $j28_matric "; 
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
   function sql_query_file ( $j28_codigo=null,$j28_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalclogmat ";
     $sql2 = "";
     if($dbwhere==""){
       if($j28_codigo!=null ){
         $sql2 .= " where iptucalclogmat.j28_codigo = $j28_codigo "; 
       } 
       if($j28_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptucalclogmat.j28_matric = $j28_matric "; 
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