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
//CLASSE DA ENTIDADE sau_medicamentosreceita
class cl_sau_medicamentosreceita { 
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
   var $s159_i_codigo = 0; 
   var $s159_i_medicamento = 0; 
   var $s159_i_receita = 0; 
   var $s159_i_formaadm = 0; 
   var $s159_n_quant = 0; 
   var $s159_t_posologia = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s159_i_codigo = int4 = Código 
                 s159_i_medicamento = int4 = Medicamento 
                 s159_i_receita = int4 = Receita 
                 s159_i_formaadm = int4 = Uso 
                 s159_n_quant = float4 = Qtde 
                 s159_t_posologia = text = Posologia 
                 ";
   //funcao construtor da classe 
   function cl_sau_medicamentosreceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_medicamentosreceita"); 
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
       $this->s159_i_codigo = ($this->s159_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_i_codigo"]:$this->s159_i_codigo);
       $this->s159_i_medicamento = ($this->s159_i_medicamento == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_i_medicamento"]:$this->s159_i_medicamento);
       $this->s159_i_receita = ($this->s159_i_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_i_receita"]:$this->s159_i_receita);
       $this->s159_i_formaadm = ($this->s159_i_formaadm == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_i_formaadm"]:$this->s159_i_formaadm);
       $this->s159_n_quant = ($this->s159_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_n_quant"]:$this->s159_n_quant);
       $this->s159_t_posologia = ($this->s159_t_posologia == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_t_posologia"]:$this->s159_t_posologia);
     }else{
       $this->s159_i_codigo = ($this->s159_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s159_i_codigo"]:$this->s159_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s159_i_codigo){ 
      $this->atualizacampos();
     if($this->s159_i_medicamento == null ){ 
       $this->erro_sql = " Campo Medicamento nao Informado.";
       $this->erro_campo = "s159_i_medicamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s159_i_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "s159_i_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s159_i_formaadm == null ){ 
       $this->erro_sql = " Campo Uso nao Informado.";
       $this->erro_campo = "s159_i_formaadm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s159_n_quant == null ){ 
       $this->erro_sql = " Campo Qtde nao Informado.";
       $this->erro_campo = "s159_n_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s159_t_posologia == null ){ 
       $this->erro_sql = " Campo Posologia nao Informado.";
       $this->erro_campo = "s159_t_posologia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s159_i_codigo == "" || $s159_i_codigo == null ){
       $result = db_query("select nextval('sau_medicamentosreceita_s159_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_medicamentosreceita_s159_i_codigo_seq do campo: s159_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s159_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_medicamentosreceita_s159_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s159_i_codigo)){
         $this->erro_sql = " Campo s159_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s159_i_codigo = $s159_i_codigo; 
       }
     }
     if(($this->s159_i_codigo == null) || ($this->s159_i_codigo == "") ){ 
       $this->erro_sql = " Campo s159_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_medicamentosreceita(
                                       s159_i_codigo 
                                      ,s159_i_medicamento 
                                      ,s159_i_receita 
                                      ,s159_i_formaadm 
                                      ,s159_n_quant 
                                      ,s159_t_posologia 
                       )
                values (
                                $this->s159_i_codigo 
                               ,$this->s159_i_medicamento 
                               ,$this->s159_i_receita 
                               ,$this->s159_i_formaadm 
                               ,$this->s159_n_quant 
                               ,'$this->s159_t_posologia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_medicamentosreceita ($this->s159_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_medicamentosreceita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_medicamentosreceita ($this->s159_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s159_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s159_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17737,'$this->s159_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3131,17737,'','".AddSlashes(pg_result($resaco,0,'s159_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3131,17740,'','".AddSlashes(pg_result($resaco,0,'s159_i_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3131,17739,'','".AddSlashes(pg_result($resaco,0,'s159_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3131,17738,'','".AddSlashes(pg_result($resaco,0,'s159_i_formaadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3131,17741,'','".AddSlashes(pg_result($resaco,0,'s159_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3131,17742,'','".AddSlashes(pg_result($resaco,0,'s159_t_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s159_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_medicamentosreceita set ";
     $virgula = "";
     if(trim($this->s159_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_i_codigo"])){ 
       $sql  .= $virgula." s159_i_codigo = $this->s159_i_codigo ";
       $virgula = ",";
       if(trim($this->s159_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s159_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s159_i_medicamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_i_medicamento"])){ 
       $sql  .= $virgula." s159_i_medicamento = $this->s159_i_medicamento ";
       $virgula = ",";
       if(trim($this->s159_i_medicamento) == null ){ 
         $this->erro_sql = " Campo Medicamento nao Informado.";
         $this->erro_campo = "s159_i_medicamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s159_i_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_i_receita"])){ 
       $sql  .= $virgula." s159_i_receita = $this->s159_i_receita ";
       $virgula = ",";
       if(trim($this->s159_i_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "s159_i_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s159_i_formaadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_i_formaadm"])){ 
       $sql  .= $virgula." s159_i_formaadm = $this->s159_i_formaadm ";
       $virgula = ",";
       if(trim($this->s159_i_formaadm) == null ){ 
         $this->erro_sql = " Campo Uso nao Informado.";
         $this->erro_campo = "s159_i_formaadm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s159_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_n_quant"])){ 
       $sql  .= $virgula." s159_n_quant = $this->s159_n_quant ";
       $virgula = ",";
       if(trim($this->s159_n_quant) == null ){ 
         $this->erro_sql = " Campo Qtde nao Informado.";
         $this->erro_campo = "s159_n_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s159_t_posologia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s159_t_posologia"])){ 
       $sql  .= $virgula." s159_t_posologia = '$this->s159_t_posologia' ";
       $virgula = ",";
       if(trim($this->s159_t_posologia) == null ){ 
         $this->erro_sql = " Campo Posologia nao Informado.";
         $this->erro_campo = "s159_t_posologia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s159_i_codigo!=null){
       $sql .= " s159_i_codigo = $this->s159_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s159_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17737,'$this->s159_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_i_codigo"]) || $this->s159_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3131,17737,'".AddSlashes(pg_result($resaco,$conresaco,'s159_i_codigo'))."','$this->s159_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_i_medicamento"]) || $this->s159_i_medicamento != "")
           $resac = db_query("insert into db_acount values($acount,3131,17740,'".AddSlashes(pg_result($resaco,$conresaco,'s159_i_medicamento'))."','$this->s159_i_medicamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_i_receita"]) || $this->s159_i_receita != "")
           $resac = db_query("insert into db_acount values($acount,3131,17739,'".AddSlashes(pg_result($resaco,$conresaco,'s159_i_receita'))."','$this->s159_i_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_i_formaadm"]) || $this->s159_i_formaadm != "")
           $resac = db_query("insert into db_acount values($acount,3131,17738,'".AddSlashes(pg_result($resaco,$conresaco,'s159_i_formaadm'))."','$this->s159_i_formaadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_n_quant"]) || $this->s159_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,3131,17741,'".AddSlashes(pg_result($resaco,$conresaco,'s159_n_quant'))."','$this->s159_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s159_t_posologia"]) || $this->s159_t_posologia != "")
           $resac = db_query("insert into db_acount values($acount,3131,17742,'".AddSlashes(pg_result($resaco,$conresaco,'s159_t_posologia'))."','$this->s159_t_posologia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_medicamentosreceita nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s159_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_medicamentosreceita nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s159_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s159_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s159_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s159_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17737,'$s159_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3131,17737,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3131,17740,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_i_medicamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3131,17739,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3131,17738,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_i_formaadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3131,17741,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3131,17742,'','".AddSlashes(pg_result($resaco,$iresaco,'s159_t_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_medicamentosreceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s159_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s159_i_codigo = $s159_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_medicamentosreceita nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s159_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_medicamentosreceita nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s159_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s159_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_medicamentosreceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s159_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_medicamentosreceita ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = sau_medicamentosreceita.s159_i_medicamento";
     $sql .= "      inner join sau_receitamedica  on  sau_receitamedica.s158_i_codigo = sau_medicamentosreceita.s159_i_receita";
     $sql .= "      inner join sau_formaadmmedicamento  on  sau_formaadmmedicamento.s160_i_codigo = sau_medicamentosreceita.s159_i_formaadm";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join far_class  on  far_class.fa05_i_codigo = far_matersaude.fa01_i_class";
     $sql .= "      left  join far_medanvisa  on  far_medanvisa.fa14_i_codigo = far_matersaude.fa01_i_medanvisa";
     $sql .= "      left  join far_prescricaomed  on  far_prescricaomed.fa31_i_codigo = far_matersaude.fa01_i_prescricaomed";
     $sql .= "      left  join far_laboratoriomed  on  far_laboratoriomed.fa32_i_codigo = far_matersaude.fa01_i_laboratoriomed";
     $sql .= "      left  join far_formafarmaceuticamed  on  far_formafarmaceuticamed.fa33_i_codigo = far_matersaude.fa01_i_formafarmaceuticamed";
     $sql .= "      left  join far_medreferenciamed  on  far_medreferenciamed.fa34_i_codigo = far_matersaude.fa01_i_medrefemed";
     $sql .= "      left  join far_listacontroladomed  on  far_listacontroladomed.fa35_i_codigo = far_matersaude.fa01_i_listacontroladomed";
     $sql .= "      left  join far_classeterapeuticamed  on  far_classeterapeuticamed.fa36_i_codigo = far_matersaude.fa01_i_classemed";
     $sql .= "      left  join far_concentracaomed  on  far_concentracaomed.fa37_i_codigo = far_matersaude.fa01_i_concentracaomed";
     $sql .= "      inner join far_medicamentohiperdia  on  far_medicamentohiperdia.fa43_i_codigo = far_matersaude.fa01_i_medhiperdia";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
     $sql2 = "";
     if($dbwhere==""){
       if($s159_i_codigo!=null ){
         $sql2 .= " where sau_medicamentosreceita.s159_i_codigo = $s159_i_codigo "; 
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
   function sql_query_file ( $s159_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_medicamentosreceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($s159_i_codigo!=null ){
         $sql2 .= " where sau_medicamentosreceita.s159_i_codigo = $s159_i_codigo "; 
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

  function sql_query_receita ( $s159_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from sau_medicamentosreceita ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = sau_medicamentosreceita.s159_i_medicamento";
     $sql .= "      inner join sau_receitamedica  on  sau_receitamedica.s158_i_codigo = sau_medicamentosreceita.s159_i_receita";
     $sql .= "      inner join sau_formaadmmedicamento  on  sau_formaadmmedicamento.s160_i_codigo = sau_medicamentosreceita.s159_i_formaadm";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sau_receitamedica.s158_i_login";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = sau_receitamedica.s158_i_tiporeceita";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = sau_receitamedica.s158_i_profissional";
     $sql .= "      inner join sau_receitaprontuario  on  sau_receitaprontuario.s162_i_receita = sau_receitamedica.s158_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($s159_i_codigo!=null ){
         $sql2 .= " where sau_medicamentosreceita.s159_i_codigo = $s159_i_codigo "; 
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