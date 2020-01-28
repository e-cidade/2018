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
//CLASSE DA ENTIDADE unidadeservicos
class cl_unidadeservicos { 
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
   var $s126_i_codigo = 0; 
   var $s126_i_unidade = 0; 
   var $s126_i_servico = 0; 
   var $s126_d_ativado_dia = null; 
   var $s126_d_ativado_mes = null; 
   var $s126_d_ativado_ano = null; 
   var $s126_d_ativado = null; 
   var $s126_d_desativado_dia = null; 
   var $s126_d_desativado_mes = null; 
   var $s126_d_desativado_ano = null; 
   var $s126_d_desativado = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s126_i_codigo = int4 = Código 
                 s126_i_unidade = int4 = Unidade 
                 s126_i_servico = int4 = Serviço 
                 s126_d_ativado = date = Ativado 
                 s126_d_desativado = date = Desativado 
                 ";
   //funcao construtor da classe 
   function cl_unidadeservicos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("unidadeservicos"); 
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
       $this->s126_i_codigo = ($this->s126_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_i_codigo"]:$this->s126_i_codigo);
       $this->s126_i_unidade = ($this->s126_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_i_unidade"]:$this->s126_i_unidade);
       $this->s126_i_servico = ($this->s126_i_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_i_servico"]:$this->s126_i_servico);
       if($this->s126_d_ativado == ""){
         $this->s126_d_ativado_dia = ($this->s126_d_ativado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_dia"]:$this->s126_d_ativado_dia);
         $this->s126_d_ativado_mes = ($this->s126_d_ativado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_mes"]:$this->s126_d_ativado_mes);
         $this->s126_d_ativado_ano = ($this->s126_d_ativado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_ano"]:$this->s126_d_ativado_ano);
         if($this->s126_d_ativado_dia != ""){
            $this->s126_d_ativado = $this->s126_d_ativado_ano."-".$this->s126_d_ativado_mes."-".$this->s126_d_ativado_dia;
         }
       }
       if($this->s126_d_desativado == ""){
         $this->s126_d_desativado_dia = ($this->s126_d_desativado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_dia"]:$this->s126_d_desativado_dia);
         $this->s126_d_desativado_mes = ($this->s126_d_desativado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_mes"]:$this->s126_d_desativado_mes);
         $this->s126_d_desativado_ano = ($this->s126_d_desativado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_ano"]:$this->s126_d_desativado_ano);
         if($this->s126_d_desativado_dia != ""){
            $this->s126_d_desativado = $this->s126_d_desativado_ano."-".$this->s126_d_desativado_mes."-".$this->s126_d_desativado_dia;
         }
       }
     }else{
       $this->s126_i_codigo = ($this->s126_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s126_i_codigo"]:$this->s126_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s126_i_codigo){ 
      $this->atualizacampos();
     if($this->s126_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "s126_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s126_i_servico == null ){ 
       $this->erro_sql = " Campo Serviço nao Informado.";
       $this->erro_campo = "s126_i_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s126_d_ativado == null ){ 
       $this->s126_d_ativado = "null";
     }
     if($this->s126_d_desativado == null ){ 
       $this->s126_d_desativado = "null";
     }
     if($s126_i_codigo == "" || $s126_i_codigo == null ){
       $result = db_query("select nextval('unidadeservicos_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: unidadeservicos_codigo_seq do campo: s126_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s126_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from unidadeservicos_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s126_i_codigo)){
         $this->erro_sql = " Campo s126_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s126_i_codigo = $s126_i_codigo; 
       }
     }
     if(($this->s126_i_codigo == null) || ($this->s126_i_codigo == "") ){ 
       $this->erro_sql = " Campo s126_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into unidadeservicos(
                                       s126_i_codigo 
                                      ,s126_i_unidade 
                                      ,s126_i_servico 
                                      ,s126_d_ativado 
                                      ,s126_d_desativado 
                       )
                values (
                                $this->s126_i_codigo 
                               ,$this->s126_i_unidade 
                               ,$this->s126_i_servico 
                               ,".($this->s126_d_ativado == "null" || $this->s126_d_ativado == ""?"null":"'".$this->s126_d_ativado."'")." 
                               ,".($this->s126_d_desativado == "null" || $this->s126_d_desativado == ""?"null":"'".$this->s126_d_desativado."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidade Servicos Classificacao ($this->s126_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidade Servicos Classificacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidade Servicos Classificacao ($this->s126_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s126_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s126_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15185,'$this->s126_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2674,15185,'','".AddSlashes(pg_result($resaco,0,'s126_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2674,15189,'','".AddSlashes(pg_result($resaco,0,'s126_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2674,15186,'','".AddSlashes(pg_result($resaco,0,'s126_i_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2674,15187,'','".AddSlashes(pg_result($resaco,0,'s126_d_ativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2674,15188,'','".AddSlashes(pg_result($resaco,0,'s126_d_desativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s126_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update unidadeservicos set ";
     $virgula = "";
     if(trim($this->s126_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s126_i_codigo"])){ 
       $sql  .= $virgula." s126_i_codigo = $this->s126_i_codigo ";
       $virgula = ",";
       if(trim($this->s126_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s126_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s126_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s126_i_unidade"])){ 
       $sql  .= $virgula." s126_i_unidade = $this->s126_i_unidade ";
       $virgula = ",";
       if(trim($this->s126_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "s126_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s126_i_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s126_i_servico"])){ 
       $sql  .= $virgula." s126_i_servico = $this->s126_i_servico ";
       $virgula = ",";
       if(trim($this->s126_i_servico) == null ){ 
         $this->erro_sql = " Campo Serviço nao Informado.";
         $this->erro_campo = "s126_i_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s126_d_ativado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_dia"] !="") ){ 
       $sql  .= $virgula." s126_d_ativado = '$this->s126_d_ativado' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s126_d_ativado_dia"])){ 
         $sql  .= $virgula." s126_d_ativado = null ";
         $virgula = ",";
       }
     }
     if(trim($this->s126_d_desativado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_dia"] !="") ){ 
       $sql  .= $virgula." s126_d_desativado = '$this->s126_d_desativado' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s126_d_desativado_dia"])){ 
         $sql  .= $virgula." s126_d_desativado = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($s126_i_codigo!=null){
       $sql .= " s126_i_codigo = $this->s126_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s126_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15185,'$this->s126_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s126_i_codigo"]) || $this->s126_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2674,15185,'".AddSlashes(pg_result($resaco,$conresaco,'s126_i_codigo'))."','$this->s126_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s126_i_unidade"]) || $this->s126_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2674,15189,'".AddSlashes(pg_result($resaco,$conresaco,'s126_i_unidade'))."','$this->s126_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s126_i_servico"]) || $this->s126_i_servico != "")
           $resac = db_query("insert into db_acount values($acount,2674,15186,'".AddSlashes(pg_result($resaco,$conresaco,'s126_i_servico'))."','$this->s126_i_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s126_d_ativado"]) || $this->s126_d_ativado != "")
           $resac = db_query("insert into db_acount values($acount,2674,15187,'".AddSlashes(pg_result($resaco,$conresaco,'s126_d_ativado'))."','$this->s126_d_ativado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s126_d_desativado"]) || $this->s126_d_desativado != "")
           $resac = db_query("insert into db_acount values($acount,2674,15188,'".AddSlashes(pg_result($resaco,$conresaco,'s126_d_desativado'))."','$this->s126_d_desativado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade Servicos Classificacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s126_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidade Servicos Classificacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s126_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s126_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s126_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s126_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15185,'$s126_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2674,15185,'','".AddSlashes(pg_result($resaco,$iresaco,'s126_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2674,15189,'','".AddSlashes(pg_result($resaco,$iresaco,'s126_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2674,15186,'','".AddSlashes(pg_result($resaco,$iresaco,'s126_i_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2674,15187,'','".AddSlashes(pg_result($resaco,$iresaco,'s126_d_ativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2674,15188,'','".AddSlashes(pg_result($resaco,$iresaco,'s126_d_desativado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from unidadeservicos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s126_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s126_i_codigo = $s126_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade Servicos Classificacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s126_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidade Servicos Classificacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s126_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s126_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:unidadeservicos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s126_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from unidadeservicos ";
     $sql .= "      inner join sau_servclassificacao  on  sau_servclassificacao.sd87_i_codigo = unidadeservicos.s126_i_servico";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidadeservicos.s126_i_unidade";
     $sql .= "      inner join sau_servico  on  sau_servico.sd86_i_codigo = sau_servclassificacao.sd87_i_servico";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = unidades.sd02_i_diretor and  cgm.z01_numcgm = unidades.sd02_i_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left  join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left  join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left  join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left  join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left  join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left  join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left  join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql2 = "";
     if($dbwhere==""){
       if($s126_i_codigo!=null ){
         $sql2 .= " where unidadeservicos.s126_i_codigo = $s126_i_codigo "; 
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
   function sql_query_file ( $s126_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from unidadeservicos ";
     $sql2 = "";
     if($dbwhere==""){
       if($s126_i_codigo!=null ){
         $sql2 .= " where unidadeservicos.s126_i_codigo = $s126_i_codigo "; 
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