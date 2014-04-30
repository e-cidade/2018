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

//MODULO: farmacia
//CLASSE DA ENTIDADE far_retirada
class cl_far_retirada { 
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
   var $fa04_i_codigo = 0; 
   var $fa04_c_numeroreceita = null; 
   var $fa04_d_dtvalidade_dia = null; 
   var $fa04_d_dtvalidade_mes = null; 
   var $fa04_d_dtvalidade_ano = null; 
   var $fa04_d_dtvalidade = null; 
   var $fa04_i_unidades = 0; 
   var $fa04_i_cgsund = 0; 
   var $fa04_i_tiporeceita = 0; 
   var $fa04_i_dbusuario = 0; 
   var $fa04_d_data_dia = null; 
   var $fa04_d_data_mes = null; 
   var $fa04_d_data_ano = null; 
   var $fa04_d_data = null; 
   var $fa04_i_profissional = 0; 
   var $fa04_i_receita = 0; 
   var $fa04_tiporetirada = 0; 
   var $fa04_numeronotificacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa04_i_codigo = int4 = Retirada 
                 fa04_c_numeroreceita = char(10) = Número receita 
                 fa04_d_dtvalidade = date = Data validade 
                 fa04_i_unidades = int4 = Unidades 
                 fa04_i_cgsund = int4 = CGS 
                 fa04_i_tiporeceita = int4 = Tipo receita 
                 fa04_i_dbusuario = int4 = Usuario 
                 fa04_d_data = date = Data 
                 fa04_i_profissional = int4 = Profissional 
                 fa04_i_receita = int4 = Receita 
                 fa04_tiporetirada = int4 = Tipo de Retirada 
                 fa04_numeronotificacao = int8 = Número de Notificação 
                 ";
   //funcao construtor da classe 
   function cl_far_retirada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_retirada"); 
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
       $this->fa04_i_codigo = ($this->fa04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]:$this->fa04_i_codigo);
       $this->fa04_c_numeroreceita = ($this->fa04_c_numeroreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"]:$this->fa04_c_numeroreceita);
       if($this->fa04_d_dtvalidade == ""){
         $this->fa04_d_dtvalidade_dia = ($this->fa04_d_dtvalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"]:$this->fa04_d_dtvalidade_dia);
         $this->fa04_d_dtvalidade_mes = ($this->fa04_d_dtvalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_mes"]:$this->fa04_d_dtvalidade_mes);
         $this->fa04_d_dtvalidade_ano = ($this->fa04_d_dtvalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_ano"]:$this->fa04_d_dtvalidade_ano);
         if($this->fa04_d_dtvalidade_dia != ""){
            $this->fa04_d_dtvalidade = $this->fa04_d_dtvalidade_ano."-".$this->fa04_d_dtvalidade_mes."-".$this->fa04_d_dtvalidade_dia;
         }
       }
       $this->fa04_i_unidades = ($this->fa04_i_unidades == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"]:$this->fa04_i_unidades);
       $this->fa04_i_cgsund = ($this->fa04_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"]:$this->fa04_i_cgsund);
       $this->fa04_i_tiporeceita = ($this->fa04_i_tiporeceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"]:$this->fa04_i_tiporeceita);
       $this->fa04_i_dbusuario = ($this->fa04_i_dbusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"]:$this->fa04_i_dbusuario);
       if($this->fa04_d_data == ""){
         $this->fa04_d_data_dia = ($this->fa04_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"]:$this->fa04_d_data_dia);
         $this->fa04_d_data_mes = ($this->fa04_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_mes"]:$this->fa04_d_data_mes);
         $this->fa04_d_data_ano = ($this->fa04_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_d_data_ano"]:$this->fa04_d_data_ano);
         if($this->fa04_d_data_dia != ""){
            $this->fa04_d_data = $this->fa04_d_data_ano."-".$this->fa04_d_data_mes."-".$this->fa04_d_data_dia;
         }
       }
       $this->fa04_i_profissional = ($this->fa04_i_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"]:$this->fa04_i_profissional);
       $this->fa04_i_receita = ($this->fa04_i_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_receita"]:$this->fa04_i_receita);
       $this->fa04_tiporetirada = ($this->fa04_tiporetirada == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_tiporetirada"]:$this->fa04_tiporetirada);
       $this->fa04_numeronotificacao = ($this->fa04_numeronotificacao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_numeronotificacao"]:$this->fa04_numeronotificacao);
     }else{
       $this->fa04_i_codigo = ($this->fa04_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]:$this->fa04_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa04_i_codigo){ 
      $this->atualizacampos();
     if($this->fa04_d_dtvalidade == null ){ 
       $this->fa04_d_dtvalidade = "null";
     }
     if($this->fa04_i_unidades == null ){ 
       $this->erro_sql = " Campo Unidades nao Informado.";
       $this->erro_campo = "fa04_i_unidades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "fa04_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_tiporeceita == null ){ 
       $this->erro_sql = " Campo Tipo receita nao Informado.";
       $this->erro_campo = "fa04_i_tiporeceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_dbusuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "fa04_i_dbusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "fa04_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_i_profissional == null ){ 
       $this->fa04_i_profissional = "null";
     }
     if($this->fa04_i_receita == null ){ 
       $this->fa04_i_receita = "null";
     }
     if($this->fa04_tiporetirada == null ){ 
       $this->erro_sql = " Campo Tipo de Retirada nao Informado.";
       $this->erro_campo = "fa04_tiporetirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa04_numeronotificacao == null ){ 
       $this->fa04_numeronotificacao = "0";
     }
     if($fa04_i_codigo == "" || $fa04_i_codigo == null ){
       $result = db_query("select nextval('farretirada_fa04_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: farretirada_fa04_i_codigo_seq do campo: fa04_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa04_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from farretirada_fa04_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa04_i_codigo)){
         $this->erro_sql = " Campo fa04_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa04_i_codigo = $fa04_i_codigo; 
       }
     }
     if(($this->fa04_i_codigo == null) || ($this->fa04_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa04_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_retirada(
                                       fa04_i_codigo 
                                      ,fa04_c_numeroreceita 
                                      ,fa04_d_dtvalidade 
                                      ,fa04_i_unidades 
                                      ,fa04_i_cgsund 
                                      ,fa04_i_tiporeceita 
                                      ,fa04_i_dbusuario 
                                      ,fa04_d_data 
                                      ,fa04_i_profissional 
                                      ,fa04_i_receita 
                                      ,fa04_tiporetirada 
                                      ,fa04_numeronotificacao 
                       )
                values (
                                $this->fa04_i_codigo 
                               ,'$this->fa04_c_numeroreceita' 
                               ,".($this->fa04_d_dtvalidade == "null" || $this->fa04_d_dtvalidade == ""?"null":"'".$this->fa04_d_dtvalidade."'")." 
                               ,$this->fa04_i_unidades 
                               ,$this->fa04_i_cgsund 
                               ,$this->fa04_i_tiporeceita 
                               ,$this->fa04_i_dbusuario 
                               ,".($this->fa04_d_data == "null" || $this->fa04_d_data == ""?"null":"'".$this->fa04_d_data."'")." 
                               ,$this->fa04_i_profissional 
                               ,$this->fa04_i_receita 
                               ,$this->fa04_tiporetirada 
                               ,$this->fa04_numeronotificacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_retirada ($this->fa04_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_retirada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_retirada ($this->fa04_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa04_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12143,'$this->fa04_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2106,12143,'','".AddSlashes(pg_result($resaco,0,'fa04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12144,'','".AddSlashes(pg_result($resaco,0,'fa04_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12147,'','".AddSlashes(pg_result($resaco,0,'fa04_d_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12148,'','".AddSlashes(pg_result($resaco,0,'fa04_i_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12149,'','".AddSlashes(pg_result($resaco,0,'fa04_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12150,'','".AddSlashes(pg_result($resaco,0,'fa04_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12187,'','".AddSlashes(pg_result($resaco,0,'fa04_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12186,'','".AddSlashes(pg_result($resaco,0,'fa04_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,12188,'','".AddSlashes(pg_result($resaco,0,'fa04_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,17754,'','".AddSlashes(pg_result($resaco,0,'fa04_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,18106,'','".AddSlashes(pg_result($resaco,0,'fa04_tiporetirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2106,20167,'','".AddSlashes(pg_result($resaco,0,'fa04_numeronotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa04_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_retirada set ";
     $virgula = "";
     if(trim($this->fa04_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"])){ 
       $sql  .= $virgula." fa04_i_codigo = $this->fa04_i_codigo ";
       $virgula = ",";
       if(trim($this->fa04_i_codigo) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "fa04_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_c_numeroreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"])){ 
       $sql  .= $virgula." fa04_c_numeroreceita = '$this->fa04_c_numeroreceita' ";
       $virgula = ",";
     }
     if(trim($this->fa04_d_dtvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"] !="") ){ 
       $sql  .= $virgula." fa04_d_dtvalidade = '$this->fa04_d_dtvalidade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade_dia"])){ 
         $sql  .= $virgula." fa04_d_dtvalidade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->fa04_i_unidades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"])){ 
       $sql  .= $virgula." fa04_i_unidades = $this->fa04_i_unidades ";
       $virgula = ",";
       if(trim($this->fa04_i_unidades) == null ){ 
         $this->erro_sql = " Campo Unidades nao Informado.";
         $this->erro_campo = "fa04_i_unidades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"])){ 
       $sql  .= $virgula." fa04_i_cgsund = $this->fa04_i_cgsund ";
       $virgula = ",";
       if(trim($this->fa04_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "fa04_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_tiporeceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"])){ 
       $sql  .= $virgula." fa04_i_tiporeceita = $this->fa04_i_tiporeceita ";
       $virgula = ",";
       if(trim($this->fa04_i_tiporeceita) == null ){ 
         $this->erro_sql = " Campo Tipo receita nao Informado.";
         $this->erro_campo = "fa04_i_tiporeceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_i_dbusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"])){ 
       $sql  .= $virgula." fa04_i_dbusuario = $this->fa04_i_dbusuario ";
       $virgula = ",";
       if(trim($this->fa04_i_dbusuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "fa04_i_dbusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"] !="") ){ 
       $sql  .= $virgula." fa04_d_data = '$this->fa04_d_data' ";
       $virgula = ",";
       if(trim($this->fa04_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "fa04_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data_dia"])){ 
         $sql  .= $virgula." fa04_d_data = null ";
         $virgula = ",";
         if(trim($this->fa04_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "fa04_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa04_i_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"])){ 
        if(trim($this->fa04_i_profissional)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"])){ 
           $this->fa04_i_profissional = "null" ; 
        } 
       $sql  .= $virgula." fa04_i_profissional = $this->fa04_i_profissional ";
       $virgula = ",";
     }
     if(trim($this->fa04_i_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_receita"])){ 
        if(trim($this->fa04_i_receita)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_receita"])){ 
           $this->fa04_i_receita = "0" ; 
        } 
       $sql  .= $virgula." fa04_i_receita = $this->fa04_i_receita ";
       $virgula = ",";
     }
     if(trim($this->fa04_tiporetirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_tiporetirada"])){ 
       $sql  .= $virgula." fa04_tiporetirada = $this->fa04_tiporetirada ";
       $virgula = ",";
       if(trim($this->fa04_tiporetirada) == null ){ 
         $this->erro_sql = " Campo Tipo de Retirada nao Informado.";
         $this->erro_campo = "fa04_tiporetirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa04_numeronotificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa04_numeronotificacao"])){ 
        if(trim($this->fa04_numeronotificacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa04_numeronotificacao"])){ 
           $this->fa04_numeronotificacao = "0" ; 
        } 
       $sql  .= $virgula." fa04_numeronotificacao = $this->fa04_numeronotificacao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($fa04_i_codigo!=null){
       $sql .= " fa04_i_codigo = $this->fa04_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa04_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12143,'$this->fa04_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_codigo"]) || $this->fa04_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2106,12143,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_codigo'))."','$this->fa04_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_c_numeroreceita"]) || $this->fa04_c_numeroreceita != "")
             $resac = db_query("insert into db_acount values($acount,2106,12144,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_c_numeroreceita'))."','$this->fa04_c_numeroreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_dtvalidade"]) || $this->fa04_d_dtvalidade != "")
             $resac = db_query("insert into db_acount values($acount,2106,12147,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_d_dtvalidade'))."','$this->fa04_d_dtvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_unidades"]) || $this->fa04_i_unidades != "")
             $resac = db_query("insert into db_acount values($acount,2106,12148,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_unidades'))."','$this->fa04_i_unidades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_cgsund"]) || $this->fa04_i_cgsund != "")
             $resac = db_query("insert into db_acount values($acount,2106,12149,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_cgsund'))."','$this->fa04_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_tiporeceita"]) || $this->fa04_i_tiporeceita != "")
             $resac = db_query("insert into db_acount values($acount,2106,12150,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_tiporeceita'))."','$this->fa04_i_tiporeceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_dbusuario"]) || $this->fa04_i_dbusuario != "")
             $resac = db_query("insert into db_acount values($acount,2106,12187,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_dbusuario'))."','$this->fa04_i_dbusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_d_data"]) || $this->fa04_d_data != "")
             $resac = db_query("insert into db_acount values($acount,2106,12186,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_d_data'))."','$this->fa04_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_profissional"]) || $this->fa04_i_profissional != "")
             $resac = db_query("insert into db_acount values($acount,2106,12188,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_profissional'))."','$this->fa04_i_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_i_receita"]) || $this->fa04_i_receita != "")
             $resac = db_query("insert into db_acount values($acount,2106,17754,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_i_receita'))."','$this->fa04_i_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_tiporetirada"]) || $this->fa04_tiporetirada != "")
             $resac = db_query("insert into db_acount values($acount,2106,18106,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_tiporetirada'))."','$this->fa04_tiporetirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["fa04_numeronotificacao"]) || $this->fa04_numeronotificacao != "")
             $resac = db_query("insert into db_acount values($acount,2106,20167,'".AddSlashes(pg_result($resaco,$conresaco,'fa04_numeronotificacao'))."','$this->fa04_numeronotificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retirada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retirada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa04_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($fa04_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12143,'$fa04_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2106,12143,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12144,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12147,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_d_dtvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12148,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_unidades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12149,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12150,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_tiporeceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12187,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_dbusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12186,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,12188,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,17754,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_i_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,18106,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_tiporetirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2106,20167,'','".AddSlashes(pg_result($resaco,$iresaco,'fa04_numeronotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from far_retirada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa04_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa04_i_codigo = $fa04_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retirada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa04_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retirada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa04_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa04_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_retirada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
function sql_query ( $fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retirada ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
     $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
     $sql .= "      left join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_retirada.fa04_i_cgsund";
     $sql .= "      left join far_retiradarequisitante  on  far_retiradarequisitante.fa08_i_retirada = far_retirada.fa04_i_codigo";
     $sql .= "      left join cgs_und b  on b.z01_i_cgsund= far_retiradarequisitante.fa08_i_codigo";
     $sql .= "      inner join far_retiradarequi  on far_retiradarequi.fa07_i_retirada= far_retirada.fa04_i_codigo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     ////foi acrescentado 3 inner join/// cgsm, retiradarequi, db_depart
    // $sql .= "      inner join cgm  on  cgm.z01_numcgm = unidades.sd02_i_diretor and  cgm.z01_numcgm = unidades.sd02_i_numcgm";
    // $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     //$sql .= "      inner join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     //$sql .= "      inner join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
    // $sql .= "      inner join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
    // $sql .= "      inner join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     //$sql .= "      inner join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     //$sql .= "      inner join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     //$sql .= "      inner join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     //$sql .= "      inner join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
    // $sql .= "      inner join cgm  as a on   a.z01_numcgm = medicos.sd03_i_cgm";
    // $sql .= "      inner join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
    // $sql .= "      inner join cgs  as b on   b.z01_i_numcgs = cgs_und.z01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($fa04_i_codigo!=null ){
         $sql2 .= " where far_retirada.fa04_i_codigo = $fa04_i_codigo "; 
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
   function sql_query_file ( $fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retirada ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa04_i_codigo!=null ){
         $sql2 .= " where far_retirada.fa04_i_codigo = $fa04_i_codigo "; 
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
  function sql_query_retiradas ($fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
  
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
    $sql .= " from far_retirada ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
    $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
    $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
    $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_retirada.fa04_i_cgsund";
    $sql .= "      left  join far_retiradarequi  on far_retiradarequi.fa07_i_retirada= far_retirada.fa04_i_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($fa04_i_codigo!=null ){
        $sql2 .= " where far_retirada.fa04_i_codigo = $fa04_i_codigo ";
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
  function sql_query_nova ( $fa04_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from far_retirada ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
    $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
    $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
    $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
    $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_retirada.fa04_i_cgsund";
    $sql .= "      left  join far_retiradarequisitante  on  far_retiradarequisitante.fa08_i_retirada = far_retirada.fa04_i_codigo";
    $sql .= "      left  join cgs_und b  on b.z01_i_cgsund= far_retiradarequisitante.fa08_i_codigo";
    $sql .= "      left  join far_retiradarequi  on far_retiradarequi.fa07_i_retirada= far_retirada.fa04_i_codigo";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($fa04_i_codigo!=null ){
        $sql2 .= " where far_retirada.fa04_i_codigo = $fa04_i_codigo ";
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
   function sql_query_geral($iFa04_i_codigo=null, $sCampos="*", $sOrdem=null, $sWhere="") { 

    $sSql = "select ";
    if ($sCampos != "*" ) {
    
      $sCampos_sql = split("#", $sCampos);
      $sVirgula    = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {
    
        $sSql    .= $sVirgula.$sCampos_sql[$i];
        $sVirgula = ",";
      
      }
      
    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from far_retirada ";
    // Basico obrigatorio (CGS, Medicamento, Usuario, departamento, Tipo receita)
    $sSql .= "  inner join far_retiradaitens        on  fa06_i_retirada     = far_retirada.fa04_i_codigo";
    $sSql .= "  inner join far_matersaude           on  fa01_i_codigo       = far_retiradaitens.fa06_i_matersaude";
    $sSql .= "  inner join matmater                 on  m60_codmater        = far_matersaude.fa01_i_codmater";
    $sSql .= "  inner join cgs_und                  on  z01_i_cgsund        = far_retirada.fa04_i_cgsund";
    $sSql .= "  inner join far_tiporeceita          on  fa03_i_codigo       = far_retirada.fa04_i_tiporeceita";
    $sSql .= "  inner join db_usuarios              on  id_usuario          = far_retirada.fa04_i_dbusuario";
    $sSql .= "  inner join unidades                 on  sd02_i_codigo       = far_retirada.fa04_i_unidades";
    $sSql .= "  inner join db_depart                on  coddepto            = unidades.sd02_i_codigo";
    // Profissional
    $sSql .= "  left  join medicos                  on  sd03_i_codigo       = far_retirada.fa04_i_profissional";
    $sSql .= "  left  join cgm                      on  z01_numcgm          = medicos.sd03_i_cgm";
    $sSql .= "  left  join cgmdoc                   on  z02_i_cgm           = cgm.z01_numcgm          ";
    $sSql .= "  left  join sau_medicosforarede      on  s154_i_medico       = medicos.sd03_i_codigo";
    // Requisitante
    $sSql .= "  left  join far_retiradarequisitante on  fa08_i_retirada     = far_retirada.fa04_i_codigo";
    $sSql .= "  left  join far_requisitantecgs      on  fa38_i_requisitante = far_retiradarequisitante.fa08_i_codigo";
    $sSql .= "  left  join cgs_und a                on  a.z01_i_cgsund      = far_requisitantecgs.fa38_i_cgs";
    $sSql .= "  left  join far_requisitanteoutro    on  fa39_i_requisitante = far_retiradarequisitante.fa08_i_codigo";
    // Material
    $sSql .= "  left join far_retiradarequi         on  fa07_i_retirada     = far_retirada.fa04_i_codigo ";
    $sSql .= "  left join matrequi                  on  m40_codigo          = far_retiradarequi.fa07_i_matrequi ";
    $sSql .= "  left join matrequiitem              on  m41_codmatrequi     = matrequi.m40_codigo and ";
    $sSql .= "                                          m41_codmatmater     = matmater.m60_codmater ";
    $sSql .= "  inner join matunid b                on  b.m61_codmatunid    = matmater.m60_codmatunid";
    $sSql .= "  inner join matmaterunisai           on  m62_codmater        = matmater.m60_codmater";
    $sSql .= "  inner join matunid c                on  c.m61_codmatunid    = matmaterunisai.m62_codmatunid";
    $sSql .= "  left  join atendrequiitem           on  m43_codmatrequiitem = matrequiitem.m41_codigo";
    $sSql .= "  left  join atendrequi               on  m42_codigo          = atendrequiitem.m43_codatendrequi ";
    $sSql2 = "";
    if ($sWhere == "") {

      if ($iFa04_i_codigo!=null ) {
        $sSql2 .= " where far_retirada.fa04_i_codigo = $iFa04_i_codigo "; 
      }

    } elseif ($sWhere != "") {
      $sSql2 = " where $sWhere";
    }
    $sSql .= $sSql2;
    if ($sOrdem != null ) {

      $sSql        .= " order by ";
      $sCampos_sql  = split("#",$sOrdem);
      $sVirgula     = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {
      	
        $sSql     .= $sVirgula.$sCampos_sql[$i];
        $sVirgula  = ",";
        
      }
    }
    return $sSql;
  }   
   public function sql_query_dados_retirada ($fa04_i_codigo = null, $campos ="*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from far_retirada ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
    $sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
    $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
    $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_retirada.fa04_i_cgsund";
    $sql .= "      left  join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";
    $sql .= "      left  join cgm  on  cgm.z01_numcgm = medicos.sd03_i_cgm";
    $sql .= "      left  join far_retiradarequi  on far_retiradarequi.fa07_i_retirada = far_retirada.fa04_i_codigo";
    $sql .= "      left  join matrequi           on far_retiradarequi.fa07_i_matrequi = m40_codigo";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($fa04_i_codigo != null) {
        $sql2 .= " where far_retirada.fa04_i_codigo = $fa04_i_codigo "; 
      } 
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i     = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   /*Função sql_query_devov faz referencia ao arquivo far1_far_devovlistamed001.php,
* Devolução de medicamentos:Cancelamento ou Devolucao;
*/
  function sql_query_devov ($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql  = "";
    $sSql .= "select ";

    if ($sCampos != '*') {

      $sCampos_sql = split("#",$sCampos);
      $sVirgula = "";

      for ($iCont = 0; $iCont < sizeof($sCampos_sql); $iCont++){

        $sSql .= $sVirgula.$sCampos_sql[$iCont];
        $sVirgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from far_retirada ";
    $sSql .= "      inner join far_retiradaitens   on  fa06_i_retirada         = fa04_i_codigo";
    $sSql .= "      inner join far_matersaude      on  fa06_i_matersaude       = fa01_i_codigo";
    $sSql .= "      left join far_retiradarequi    on  fa04_i_codigo           = fa07_i_retirada";
    $sSql .= "      left join matrequi             on  fa07_i_matrequi         = m40_codigo";
    $sSql .= "      left join matrequiitem         on  m41_codmatrequi         = m40_codigo";
    $sSql .= "      and  m41_codmatmater = fa01_i_codmater";
    $sSql .= "      left join atendrequiitem       on  m43_codmatrequiitem     = m41_codigo";
    $sSql .= "      left join matunid              on  m41_codunid             = m61_codmatunid";
    $sSql .= "      left join matmater             on  m41_codmatmater         = m60_codmater";
    $sSql .= "      left join matestoqueinimeiari  on  m49_codatendrequiitem   = m43_codigo";
    $sSql .= "      left join matestoqueinimei     on  m49_codmatestoqueinimei = m82_codigo";
    $sSql .= "      left join far_retiradaitemlote on  fa09_i_retiradaitens    = fa06_i_codigo";
    $sSql .= "      left join matestoqueitem       on  fa09_i_matestoqueitem   = m71_codlanc";
    $sSql .= "      left join matestoqueitemlote   on  m77_matestoqueitem      = m71_codlanc";
    $sSql .= "      left join far_devolucaomed     on  fa23_i_retiradaitens    = fa06_i_codigo";



    if ($sDbWhere == '') {

      if ($iCodigo != null ) {
        $sSql2 .= " where far_retirada.fa04_i_codigo = $iCodigo ";

      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
}
?>