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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE prontagendamento
class cl_prontagendamento { 
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
   var $s102_i_codigo = 0; 
   var $s102_i_prontuario = 0; 
   var $s102_i_agendamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s102_i_codigo = int4 = Código 
                 s102_i_prontuario = int4 = Prontuario 
                 s102_i_agendamento = int4 = Agendamento 
                 ";
   //funcao construtor da classe 
   function cl_prontagendamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontagendamento"); 
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
       $this->s102_i_codigo = ($this->s102_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s102_i_codigo"]:$this->s102_i_codigo);
       $this->s102_i_prontuario = ($this->s102_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["s102_i_prontuario"]:$this->s102_i_prontuario);
       $this->s102_i_agendamento = ($this->s102_i_agendamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s102_i_agendamento"]:$this->s102_i_agendamento);
     }else{
       $this->s102_i_codigo = ($this->s102_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s102_i_codigo"]:$this->s102_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s102_i_codigo){ 
      $this->atualizacampos();
     if($this->s102_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuario nao Informado.";
       $this->erro_campo = "s102_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s102_i_agendamento == null ){ 
       $this->erro_sql = " Campo Agendamento nao Informado.";
       $this->erro_campo = "s102_i_agendamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s102_i_codigo == "" || $s102_i_codigo == null ){
       $result = db_query("select nextval('prontagendametno_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontagendametno_codigo_seq do campo: s102_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s102_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontagendametno_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s102_i_codigo)){
         $this->erro_sql = " Campo s102_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s102_i_codigo = $s102_i_codigo; 
       }
     }
     if(($this->s102_i_codigo == null) || ($this->s102_i_codigo == "") ){ 
       $this->erro_sql = " Campo s102_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontagendamento(
                                       s102_i_codigo 
                                      ,s102_i_prontuario 
                                      ,s102_i_agendamento 
                       )
                values (
                                $this->s102_i_codigo 
                               ,$this->s102_i_prontuario 
                               ,$this->s102_i_agendamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Porntuário Agendamento ($this->s102_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Porntuário Agendamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Porntuário Agendamento ($this->s102_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s102_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s102_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12675,'$this->s102_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2213,12675,'','".AddSlashes(pg_result($resaco,0,'s102_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2213,12677,'','".AddSlashes(pg_result($resaco,0,'s102_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2213,12676,'','".AddSlashes(pg_result($resaco,0,'s102_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s102_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontagendamento set ";
     $virgula = "";
     if(trim($this->s102_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s102_i_codigo"])){ 
       $sql  .= $virgula." s102_i_codigo = $this->s102_i_codigo ";
       $virgula = ",";
       if(trim($this->s102_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s102_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s102_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s102_i_prontuario"])){ 
       $sql  .= $virgula." s102_i_prontuario = $this->s102_i_prontuario ";
       $virgula = ",";
       if(trim($this->s102_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuario nao Informado.";
         $this->erro_campo = "s102_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s102_i_agendamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s102_i_agendamento"])){ 
       $sql  .= $virgula." s102_i_agendamento = $this->s102_i_agendamento ";
       $virgula = ",";
       if(trim($this->s102_i_agendamento) == null ){ 
         $this->erro_sql = " Campo Agendamento nao Informado.";
         $this->erro_campo = "s102_i_agendamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s102_i_codigo!=null){
       $sql .= " s102_i_codigo = $this->s102_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s102_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12675,'$this->s102_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s102_i_codigo"]) || $this->s102_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2213,12675,'".AddSlashes(pg_result($resaco,$conresaco,'s102_i_codigo'))."','$this->s102_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s102_i_prontuario"]) || $this->s102_i_prontuario != "")
           $resac = db_query("insert into db_acount values($acount,2213,12677,'".AddSlashes(pg_result($resaco,$conresaco,'s102_i_prontuario'))."','$this->s102_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s102_i_agendamento"]) || $this->s102_i_agendamento != "")
           $resac = db_query("insert into db_acount values($acount,2213,12676,'".AddSlashes(pg_result($resaco,$conresaco,'s102_i_agendamento'))."','$this->s102_i_agendamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Porntuário Agendamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s102_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Porntuário Agendamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s102_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s102_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12675,'$s102_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2213,12675,'','".AddSlashes(pg_result($resaco,$iresaco,'s102_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2213,12677,'','".AddSlashes(pg_result($resaco,$iresaco,'s102_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2213,12676,'','".AddSlashes(pg_result($resaco,$iresaco,'s102_i_agendamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prontagendamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s102_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s102_i_codigo = $s102_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Porntuário Agendamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s102_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Porntuário Agendamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s102_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s102_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontagendamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontagendamento ";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = prontagendamento.s102_i_agendamento";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontagendamento.s102_i_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = prontuarios.sd24_i_login";
     $sql .= "      left  join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
     $sql .= "      left  join far_programa  on  far_programa.fa12_i_codigo = prontuarios.sd24_i_acaoprog";
     $sql .= "      left  join sau_motivoatendimento  on  sau_motivoatendimento.s144_i_codigo = prontuarios.sd24_i_motivo";
     $sql .= "      left  join sau_tiposatendimento  on  sau_tiposatendimento.s145_i_codigo = prontuarios.sd24_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql .= "      left  join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
     $sql .= "      left  join cgs  as b on   b.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($s102_i_codigo!=null ){
         $sql2 .= " where prontagendamento.s102_i_codigo = $s102_i_codigo "; 
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
   function sql_query_file ( $s102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontagendamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($s102_i_codigo!=null ){
         $sql2 .= " where prontagendamento.s102_i_codigo = $s102_i_codigo "; 
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
   function sql_query_ext ( $sd23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
	     $sql .= " from agendamentos ";
	     $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
	     $sql .= "      inner join undmedhorario on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor ";
	     $sql .= "      inner join especmedico   on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
	     //$sql .= "       left join undmedhorario on  undmedhorario.sd30_i_undmed = agendamentos.sd23_i_especmed
	     //                                       and  undmedhorario.sd30_i_diasemana =  ( extract(dow from agendamentos.sd23_d_consulta ) + 1 ) 
	     //        ";
	     $sql .= "       left join sau_tipoficha on  sau_tipoficha.sd101_i_codigo = undmedhorario.sd30_i_tipoficha ";
	     $sql .= "      inner join cgs           on  cgs.z01_i_numcgs = agendamentos.sd23_i_numcgs";
	     $sql .= "      inner join cgs_und       on  cgs_und.z01_i_cgsund = cgs.z01_i_numcgs";
	     $sql .= "      left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     	 $sql .= "      left join familia  on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
     	 $sql .= "      left join microarea  on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
	     
	     $sql .= "      inner join rhcbo            on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
	     $sql .= "      inner join unidademedicos   on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
	     $sql .= "      inner join medicos          on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
	     $sql .= "      inner join cgm              on cgm.z01_numcgm = medicos.sd03_i_cgm ";
	     $sql .= "       left join prontagendamento on prontagendamento.s102_i_agendamento = agendamentos.sd23_i_codigo ";
	     //$sql .= "       left join prontuarios      on prontuarios.sd24_i_codigo = prontagendamento.s102_i_prontuario ";
       $sql .= "      left join agendaconsultaanula on agendaconsultaanula.s114_i_agendaconsulta = agendamentos.sd23_i_codigo";
	
	     $sql .= "      inner join unidades       on sd02_i_codigo           = sd04_i_unidade ";
	     $sql .= "      inner join db_depart      on db_depart.coddepto      = sd04_i_unidade ";
	     $sql .= "      left  join db_departender on db_departender.coddepto = db_depart.coddepto ";
	     $sql .= "      left  join bairro         on bairro.j13_codi         = db_departender.codbairro ";
	     $sql .= "      left  join ruas           on ruas.j14_codigo         = db_departender.codlograd ";
	     $sql .= "      left  join ruascep        on ruascep.j29_codigo      = ruas.j14_codigo ";
	     $sql .= "      left  join logradcep      on logradcep.j65_lograd    = ruas.j14_codigo ";
	     $sql .= "      left  join ceplogradouros on j65_ceplog              = cp06_codlogradouro ";
	     $sql .= "      left  join prontanulado   on sd57_i_prontuario       = s102_i_prontuario ";
	     
	     $sql2 = ' where agendaconsultaanula.s114_i_codigo is null ';
	     if($dbwhere==""){
	       if($sd23_i_codigo!=null ){
	         $sql2 .= " and agendamentos.sd23_i_codigo = $sd23_i_codigo "; 
	       } 
	     }else if($dbwhere != ""){
	       $sql2 .= " and $dbwhere";
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

   function sql_query_profissional_agendamento ( $s102_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontagendamento ";
     $sql .= "      inner join agendamentos  on  agendamentos.sd23_i_codigo = prontagendamento.s102_i_agendamento";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontagendamento.s102_i_prontuario";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = agendamentos.sd23_i_usuario";
     $sql .= "      inner join undmedhorario  on  undmedhorario.sd30_i_codigo = agendamentos.sd23_i_undmedhor";
     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = undmedhorario.sd30_i_undmed";
     $sql .= "      inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      left  join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = agendamentos.sd23_i_numcgs";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql2 = "";
     if($dbwhere==""){
       if($s102_i_codigo!=null ){
         $sql2 .= " where prontagendamento.s102_i_codigo = $s102_i_codigo "; 
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