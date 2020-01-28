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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_receitaprontuario
class cl_sau_receitaprontuario { 
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
   var $s162_i_codigo = 0; 
   var $s162_i_receita = 0; 
   var $s162_i_prontuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s162_i_codigo = int4 = Código 
                 s162_i_receita = int4 = Receita 
                 s162_i_prontuario = int4 = Prontuário 
                 ";
   //funcao construtor da classe 
   function cl_sau_receitaprontuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_receitaprontuario"); 
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
       $this->s162_i_codigo = ($this->s162_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s162_i_codigo"]:$this->s162_i_codigo);
       $this->s162_i_receita = ($this->s162_i_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["s162_i_receita"]:$this->s162_i_receita);
       $this->s162_i_prontuario = ($this->s162_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["s162_i_prontuario"]:$this->s162_i_prontuario);
     }else{
       $this->s162_i_codigo = ($this->s162_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s162_i_codigo"]:$this->s162_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s162_i_codigo){ 
      $this->atualizacampos();
     if($this->s162_i_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "s162_i_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s162_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuário nao Informado.";
       $this->erro_campo = "s162_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s162_i_codigo == "" || $s162_i_codigo == null ){
       $result = db_query("select nextval('sau_receitaprontuario_s162_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_receitaprontuario_s162_i_codigo_seq do campo: s162_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s162_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_receitaprontuario_s162_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s162_i_codigo)){
         $this->erro_sql = " Campo s162_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s162_i_codigo = $s162_i_codigo; 
       }
     }
     if(($this->s162_i_codigo == null) || ($this->s162_i_codigo == "") ){ 
       $this->erro_sql = " Campo s162_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_receitaprontuario(
                                       s162_i_codigo 
                                      ,s162_i_receita 
                                      ,s162_i_prontuario 
                       )
                values (
                                $this->s162_i_codigo 
                               ,$this->s162_i_receita 
                               ,$this->s162_i_prontuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_receitaprontuario ($this->s162_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_receitaprontuario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_receitaprontuario ($this->s162_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s162_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s162_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17751,'$this->s162_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3134,17751,'','".AddSlashes(pg_result($resaco,0,'s162_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3134,17752,'','".AddSlashes(pg_result($resaco,0,'s162_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3134,17753,'','".AddSlashes(pg_result($resaco,0,'s162_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s162_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_receitaprontuario set ";
     $virgula = "";
     if(trim($this->s162_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s162_i_codigo"])){ 
       $sql  .= $virgula." s162_i_codigo = $this->s162_i_codigo ";
       $virgula = ",";
       if(trim($this->s162_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s162_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s162_i_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s162_i_receita"])){ 
       $sql  .= $virgula." s162_i_receita = $this->s162_i_receita ";
       $virgula = ",";
       if(trim($this->s162_i_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "s162_i_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s162_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s162_i_prontuario"])){ 
       $sql  .= $virgula." s162_i_prontuario = $this->s162_i_prontuario ";
       $virgula = ",";
       if(trim($this->s162_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuário nao Informado.";
         $this->erro_campo = "s162_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s162_i_codigo!=null){
       $sql .= " s162_i_codigo = $this->s162_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s162_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17751,'$this->s162_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s162_i_codigo"]) || $this->s162_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3134,17751,'".AddSlashes(pg_result($resaco,$conresaco,'s162_i_codigo'))."','$this->s162_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s162_i_receita"]) || $this->s162_i_receita != "")
           $resac = db_query("insert into db_acount values($acount,3134,17752,'".AddSlashes(pg_result($resaco,$conresaco,'s162_i_receita'))."','$this->s162_i_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s162_i_prontuario"]) || $this->s162_i_prontuario != "")
           $resac = db_query("insert into db_acount values($acount,3134,17753,'".AddSlashes(pg_result($resaco,$conresaco,'s162_i_prontuario'))."','$this->s162_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_receitaprontuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s162_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_receitaprontuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s162_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s162_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s162_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s162_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17751,'$s162_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3134,17751,'','".AddSlashes(pg_result($resaco,$iresaco,'s162_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3134,17752,'','".AddSlashes(pg_result($resaco,$iresaco,'s162_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3134,17753,'','".AddSlashes(pg_result($resaco,$iresaco,'s162_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_receitaprontuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s162_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s162_i_codigo = $s162_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_receitaprontuario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s162_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_receitaprontuario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s162_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s162_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_receitaprontuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s162_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_receitaprontuario ";
     $sql .= "      inner join sau_receitamedica  on  sau_receitamedica.s158_i_codigo = sau_receitaprontuario.s162_i_receita";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = sau_receitaprontuario.s162_i_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s162_i_codigo!=null ){
         $sql2 .= " where sau_receitaprontuario.s162_i_codigo = $s162_i_codigo "; 
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
   function sql_query_file ( $s162_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_receitaprontuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($s162_i_codigo!=null ){
         $sql2 .= " where sau_receitaprontuario.s162_i_codigo = $s162_i_codigo "; 
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