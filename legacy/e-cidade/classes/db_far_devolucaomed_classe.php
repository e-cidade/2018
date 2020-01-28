<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_devolucaomed
class cl_far_devolucaomed { 
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
   var $fa23_i_codigo = 0; 
   var $fa23_i_quantidade = 0; 
   var $fa23_c_motivo = null; 
   var $fa23_i_devolucao = 0; 
   var $fa23_i_retiradaitens = 0; 
   var $fa23_i_cancelamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa23_i_codigo = int4 = Código 
                 fa23_i_quantidade = int4 = Quantidade 
                 fa23_c_motivo = char(40) = Motivo 
                 fa23_i_devolucao = int4 = Devolucao 
                 fa23_i_retiradaitens = int4 = Ítem da Retirada 
                 fa23_i_cancelamento = int4 = Cancelamento 
                 ";
   //funcao construtor da classe 
   function cl_far_devolucaomed() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_devolucaomed"); 
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
       $this->fa23_i_codigo = ($this->fa23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_codigo"]:$this->fa23_i_codigo);
       $this->fa23_i_quantidade = ($this->fa23_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_quantidade"]:$this->fa23_i_quantidade);
       $this->fa23_c_motivo = ($this->fa23_c_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_c_motivo"]:$this->fa23_c_motivo);
       $this->fa23_i_devolucao = ($this->fa23_i_devolucao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_devolucao"]:$this->fa23_i_devolucao);
       $this->fa23_i_retiradaitens = ($this->fa23_i_retiradaitens == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_retiradaitens"]:$this->fa23_i_retiradaitens);
       $this->fa23_i_cancelamento = ($this->fa23_i_cancelamento == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_cancelamento"]:$this->fa23_i_cancelamento);
     }else{
       $this->fa23_i_codigo = ($this->fa23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa23_i_codigo"]:$this->fa23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa23_i_codigo){ 
      $this->atualizacampos();
     if($this->fa23_i_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "fa23_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa23_c_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "fa23_c_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa23_i_devolucao == null ){ 
       $this->erro_sql = " Campo Devolucao nao Informado.";
       $this->erro_campo = "fa23_i_devolucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa23_i_retiradaitens == null ){ 
       $this->erro_sql = " Campo Ítem da Retirada nao Informado.";
       $this->erro_campo = "fa23_i_retiradaitens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa23_i_cancelamento == null ){ 
       $this->erro_sql = " Campo Cancelamento nao Informado.";
       $this->erro_campo = "fa23_i_cancelamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa23_i_codigo == "" || $fa23_i_codigo == null ){
       $result = db_query("select nextval('far_devolucaomed_fa23_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_devolucaomed_fa23_codigo_seq do campo: fa23_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa23_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_devolucaomed_fa23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa23_i_codigo)){
         $this->erro_sql = " Campo fa23_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa23_i_codigo = $fa23_i_codigo; 
       }
     }
     if(($this->fa23_i_codigo == null) || ($this->fa23_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_devolucaomed(
                                       fa23_i_codigo 
                                      ,fa23_i_quantidade 
                                      ,fa23_c_motivo 
                                      ,fa23_i_devolucao 
                                      ,fa23_i_retiradaitens 
                                      ,fa23_i_cancelamento 
                       )
                values (
                                $this->fa23_i_codigo 
                               ,$this->fa23_i_quantidade 
                               ,'$this->fa23_c_motivo' 
                               ,$this->fa23_i_devolucao 
                               ,$this->fa23_i_retiradaitens 
                               ,$this->fa23_i_cancelamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_devolucaomed ($this->fa23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_devolucaomed já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_devolucaomed ($this->fa23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa23_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14037,'$this->fa23_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2471,14037,'','".AddSlashes(pg_result($resaco,0,'fa23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2471,14039,'','".AddSlashes(pg_result($resaco,0,'fa23_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2471,14072,'','".AddSlashes(pg_result($resaco,0,'fa23_c_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2471,16234,'','".AddSlashes(pg_result($resaco,0,'fa23_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2471,17112,'','".AddSlashes(pg_result($resaco,0,'fa23_i_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2471,17113,'','".AddSlashes(pg_result($resaco,0,'fa23_i_cancelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa23_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_devolucaomed set ";
     $virgula = "";
     if(trim($this->fa23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_codigo"])){ 
       $sql  .= $virgula." fa23_i_codigo = $this->fa23_i_codigo ";
       $virgula = ",";
       if(trim($this->fa23_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa23_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_quantidade"])){ 
       $sql  .= $virgula." fa23_i_quantidade = $this->fa23_i_quantidade ";
       $virgula = ",";
       if(trim($this->fa23_i_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "fa23_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa23_c_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_c_motivo"])){ 
       $sql  .= $virgula." fa23_c_motivo = '$this->fa23_c_motivo' ";
       $virgula = ",";
       if(trim($this->fa23_c_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "fa23_c_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa23_i_devolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_devolucao"])){ 
       $sql  .= $virgula." fa23_i_devolucao = $this->fa23_i_devolucao ";
       $virgula = ",";
       if(trim($this->fa23_i_devolucao) == null ){ 
         $this->erro_sql = " Campo Devolucao nao Informado.";
         $this->erro_campo = "fa23_i_devolucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa23_i_retiradaitens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_retiradaitens"])){ 
       $sql  .= $virgula." fa23_i_retiradaitens = $this->fa23_i_retiradaitens ";
       $virgula = ",";
       if(trim($this->fa23_i_retiradaitens) == null ){ 
         $this->erro_sql = " Campo Ítem da Retirada nao Informado.";
         $this->erro_campo = "fa23_i_retiradaitens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa23_i_cancelamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_cancelamento"])){ 
       $sql  .= $virgula." fa23_i_cancelamento = $this->fa23_i_cancelamento ";
       $virgula = ",";
       if(trim($this->fa23_i_cancelamento) == null ){ 
         $this->erro_sql = " Campo Cancelamento nao Informado.";
         $this->erro_campo = "fa23_i_cancelamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa23_i_codigo!=null){
       $sql .= " fa23_i_codigo = $this->fa23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14037,'$this->fa23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_codigo"]) || $this->fa23_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2471,14037,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_i_codigo'))."','$this->fa23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_quantidade"]) || $this->fa23_i_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2471,14039,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_i_quantidade'))."','$this->fa23_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_c_motivo"]) || $this->fa23_c_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2471,14072,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_c_motivo'))."','$this->fa23_c_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_devolucao"]) || $this->fa23_i_devolucao != "")
           $resac = db_query("insert into db_acount values($acount,2471,16234,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_i_devolucao'))."','$this->fa23_i_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_retiradaitens"]) || $this->fa23_i_retiradaitens != "")
           $resac = db_query("insert into db_acount values($acount,2471,17112,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_i_retiradaitens'))."','$this->fa23_i_retiradaitens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa23_i_cancelamento"]) || $this->fa23_i_cancelamento != "")
           $resac = db_query("insert into db_acount values($acount,2471,17113,'".AddSlashes(pg_result($resaco,$conresaco,'fa23_i_cancelamento'))."','$this->fa23_i_cancelamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_devolucaomed nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_devolucaomed nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa23_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa23_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14037,'$fa23_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2471,14037,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2471,14039,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2471,14072,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_c_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2471,16234,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2471,17112,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_i_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2471,17113,'','".AddSlashes(pg_result($resaco,$iresaco,'fa23_i_cancelamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_devolucaomed
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa23_i_codigo = $fa23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_devolucaomed nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_devolucaomed nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa23_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_devolucaomed";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_devolucaomed ";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_devolucaomed.fa23_i_matersaude";
     $sql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_codigo = far_devolucaomed.fa23_i_retiradaitens";
     $sql .= "      inner join far_devolucao  on  far_devolucao.fa22_i_codigo = far_devolucaomed.fa23_i_devolucao";
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
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= "      inner join far_retirada  as a on   a.fa04_i_codigo = far_retiradaitens.fa06_i_retirada";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_devolucao.fa22_i_login";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_devolucao.fa22_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($fa23_i_codigo!=null ){
         $sql2 .= " where far_devolucaomed.fa23_i_codigo = $fa23_i_codigo "; 
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
   function sql_query_file ( $fa23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_devolucaomed ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa23_i_codigo!=null ){
         $sql2 .= " where far_devolucaomed.fa23_i_codigo = $fa23_i_codigo "; 
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
   function sql_query_devolucao ( $fa23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_devolucaomed ";
     $sql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_codigo = far_devolucaomed.fa23_i_retiradaitens";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = far_retiradaitens.fa06_i_matersaude";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = far_matersaude.fa01_i_codmater";    
     $sql2 = "";
     if($dbwhere==""){
       if($fa23_i_codigo!=null ){
         $sql2 .= " where far_devolucaomed.fa23_i_codigo = $fa23_i_codigo "; 
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

  /*Utilizada no arquivo far_devolucao001.php */

  function sql_query_devolve ( $fa23_i_codigo=null,$sCampos="*",$sOrdem=null,$sDbwhere="") { 
     
     $sSql = "select ";
     if($sCampos != "*" ){
       $sCamposSql = split("#",$sCampos);
       $sVirgula = "";

       for ($i = 0; $i < sizeof($sCamposSql); $i++) {

         $sSql .= $sVirgula.$sCamposSql[$i];
         $sVirgula = ",";

       }

     }else{
       $sSql .= $sCampos;
     }
     $sSql .= " from far_devolucaomed ";
     $sSql .= "      inner join far_devolucao      on far_devolucao.fa22_i_codigo      = far_devolucaomed.fa23_i_devolucao";
     $sSql .= "      inner join cgs_und            on cgs_und.z01_i_cgsund             = far_devolucao.fa22_i_cgsund";
     $sSql .= "      inner join cgs                on cgs.z01_i_numcgs                 = cgs_und.z01_i_cgsund";
     $sSql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_codigo = far_devolucaomed.fa23_i_retiradaitens";     
     $sSql .= "      inner join far_matersaude     on  far_matersaude.fa01_i_codigo    = far_retiradaitens.fa06_i_matersaude";
     $sSql .= "      inner join matmater           on  matmater.m60_codmater           = far_matersaude.fa01_i_codmater";
     $sSql .= "      inner join matunid            on  matunid.m61_codmatunid          = matmater.m60_codmatunid";
     $sSql2 = "";

     if ($sDbwhere == "") {
       
       if($fa23_i_codigo!=null ) {
         $sSql2 .= " where far_devolucaomed.fa23_i_codigo = $fa23_i_codigo ";
       }

     } else if($sDbwhere != ""){
       $sSql2 = " where $sDbwhere";
     }
     $sSql .= $sSql2;

     if ($sOrdem != null ) {
       $sSql .= " order by ";
       $sCamposSql = split("#",$sOrdem);
       $sVirgula = "";
       
       for ($i = 0; $i< sizeof($sCamposSql); $i++){
         
         $sSql .= $sVirgula.$sCamposSql[$i];
         $sVirgula = ",";
       
       }
     
     }
     return $sSql;
  }



}
?>