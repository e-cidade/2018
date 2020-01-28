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

//MODULO: tfd
//CLASSE DA ENTIDADE tfd_ajudacusto
class cl_tfd_ajudacusto { 
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
   var $tf12_i_codigo = 0; 
   var $tf12_i_procedimento = 0; 
   var $tf12_f_valor = 0; 
   var $tf12_d_validadeini_dia = null; 
   var $tf12_d_validadeini_mes = null; 
   var $tf12_d_validadeini_ano = null; 
   var $tf12_d_validadeini = null; 
   var $tf12_d_validadefim_dia = null; 
   var $tf12_d_validadefim_mes = null; 
   var $tf12_d_validadefim_ano = null; 
   var $tf12_d_validadefim = null; 
   var $tf12_faturabpa = 'f'; 
   var $tf12_descricao = null; 
   var $tf12_acompanhente = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf12_i_codigo = int4 = Código 
                 tf12_i_procedimento = int8 = Procedimento 
                 tf12_f_valor = float4 = Valor Unitário 
                 tf12_d_validadeini = date = Início 
                 tf12_d_validadefim = date = Fim 
                 tf12_faturabpa = bool = Fatura BPA 
                 tf12_descricao = varchar(50) = Descrição 
                 tf12_acompanhente = bool = Paciente 
                 ";
   //funcao construtor da classe 
   function cl_tfd_ajudacusto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_ajudacusto"); 
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
       $this->tf12_i_codigo = ($this->tf12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_i_codigo"]:$this->tf12_i_codigo);
       $this->tf12_i_procedimento = ($this->tf12_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_i_procedimento"]:$this->tf12_i_procedimento);
       $this->tf12_f_valor = ($this->tf12_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_f_valor"]:$this->tf12_f_valor);
       if($this->tf12_d_validadeini == ""){
         $this->tf12_d_validadeini_dia = ($this->tf12_d_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_dia"]:$this->tf12_d_validadeini_dia);
         $this->tf12_d_validadeini_mes = ($this->tf12_d_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_mes"]:$this->tf12_d_validadeini_mes);
         $this->tf12_d_validadeini_ano = ($this->tf12_d_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_ano"]:$this->tf12_d_validadeini_ano);
         if($this->tf12_d_validadeini_dia != ""){
            $this->tf12_d_validadeini = $this->tf12_d_validadeini_ano."-".$this->tf12_d_validadeini_mes."-".$this->tf12_d_validadeini_dia;
         }
       }
       if($this->tf12_d_validadefim == ""){
         $this->tf12_d_validadefim_dia = ($this->tf12_d_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_dia"]:$this->tf12_d_validadefim_dia);
         $this->tf12_d_validadefim_mes = ($this->tf12_d_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_mes"]:$this->tf12_d_validadefim_mes);
         $this->tf12_d_validadefim_ano = ($this->tf12_d_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_ano"]:$this->tf12_d_validadefim_ano);
         if($this->tf12_d_validadefim_dia != ""){
            $this->tf12_d_validadefim = $this->tf12_d_validadefim_ano."-".$this->tf12_d_validadefim_mes."-".$this->tf12_d_validadefim_dia;
         }
       }
       $this->tf12_faturabpa = ($this->tf12_faturabpa == "f"?@$GLOBALS["HTTP_POST_VARS"]["tf12_faturabpa"]:$this->tf12_faturabpa);
       $this->tf12_descricao = ($this->tf12_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_descricao"]:$this->tf12_descricao);
       $this->tf12_acompanhente = ($this->tf12_acompanhente == "f"?@$GLOBALS["HTTP_POST_VARS"]["tf12_acompanhente"]:$this->tf12_acompanhente);
     }else{
       $this->tf12_i_codigo = ($this->tf12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf12_i_codigo"]:$this->tf12_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf12_i_codigo){ 
      $this->atualizacampos();
     if($this->tf12_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento não informado.";
       $this->erro_campo = "tf12_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf12_f_valor == null ){ 
       $this->tf12_f_valor = "0";
     }
     if($this->tf12_d_validadeini == null ){ 
       $this->erro_sql = " Campo Início não informado.";
       $this->erro_campo = "tf12_d_validadeini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf12_d_validadefim == null ){ 
       $this->tf12_d_validadefim = "null";
     }
     if($this->tf12_faturabpa == null ){ 
       $this->tf12_faturabpa = "f";
     }
     if($this->tf12_acompanhente == null ){ 
       $this->tf12_acompanhente = "t";
     }
     if($tf12_i_codigo == "" || $tf12_i_codigo == null ){
       $result = db_query("select nextval('tfd_ajudacusto_tf12_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_ajudacusto_tf12_i_codigo_seq do campo: tf12_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf12_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_ajudacusto_tf12_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf12_i_codigo)){
         $this->erro_sql = " Campo tf12_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf12_i_codigo = $tf12_i_codigo; 
       }
     }
     if(($this->tf12_i_codigo == null) || ($this->tf12_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf12_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_ajudacusto(
                                       tf12_i_codigo 
                                      ,tf12_i_procedimento 
                                      ,tf12_f_valor 
                                      ,tf12_d_validadeini 
                                      ,tf12_d_validadefim 
                                      ,tf12_faturabpa 
                                      ,tf12_descricao 
                                      ,tf12_acompanhente 
                       )
                values (
                                $this->tf12_i_codigo 
                               ,$this->tf12_i_procedimento 
                               ,$this->tf12_f_valor 
                               ,".($this->tf12_d_validadeini == "null" || $this->tf12_d_validadeini == ""?"null":"'".$this->tf12_d_validadeini."'")." 
                               ,".($this->tf12_d_validadefim == "null" || $this->tf12_d_validadefim == ""?"null":"'".$this->tf12_d_validadefim."'")." 
                               ,'$this->tf12_faturabpa' 
                               ,'$this->tf12_descricao' 
                               ,'$this->tf12_acompanhente' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_ajudacusto ($this->tf12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_ajudacusto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_ajudacusto ($this->tf12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf12_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf12_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16373,'$this->tf12_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2868,16373,'','".AddSlashes(pg_result($resaco,0,'tf12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,16374,'','".AddSlashes(pg_result($resaco,0,'tf12_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,16375,'','".AddSlashes(pg_result($resaco,0,'tf12_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,16377,'','".AddSlashes(pg_result($resaco,0,'tf12_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,16378,'','".AddSlashes(pg_result($resaco,0,'tf12_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,18274,'','".AddSlashes(pg_result($resaco,0,'tf12_faturabpa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,18273,'','".AddSlashes(pg_result($resaco,0,'tf12_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2868,20337,'','".AddSlashes(pg_result($resaco,0,'tf12_acompanhente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf12_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_ajudacusto set ";
     $virgula = "";
     if(trim($this->tf12_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_i_codigo"])){ 
       $sql  .= $virgula." tf12_i_codigo = $this->tf12_i_codigo ";
       $virgula = ",";
       if(trim($this->tf12_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf12_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf12_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_i_procedimento"])){ 
       $sql  .= $virgula." tf12_i_procedimento = $this->tf12_i_procedimento ";
       $virgula = ",";
       if(trim($this->tf12_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento não informado.";
         $this->erro_campo = "tf12_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf12_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_f_valor"])){ 
        if(trim($this->tf12_f_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tf12_f_valor"])){ 
           $this->tf12_f_valor = "0" ; 
        } 
       $sql  .= $virgula." tf12_f_valor = $this->tf12_f_valor ";
       $virgula = ",";
     }
     if(trim($this->tf12_d_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." tf12_d_validadeini = '$this->tf12_d_validadeini' ";
       $virgula = ",";
       if(trim($this->tf12_d_validadeini) == null ){ 
         $this->erro_sql = " Campo Início não informado.";
         $this->erro_campo = "tf12_d_validadeini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini_dia"])){ 
         $sql  .= $virgula." tf12_d_validadeini = null ";
         $virgula = ",";
         if(trim($this->tf12_d_validadeini) == null ){ 
           $this->erro_sql = " Campo Início não informado.";
           $this->erro_campo = "tf12_d_validadeini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf12_d_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." tf12_d_validadefim = '$this->tf12_d_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim_dia"])){ 
         $sql  .= $virgula." tf12_d_validadefim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->tf12_faturabpa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_faturabpa"])){ 
       $sql  .= $virgula." tf12_faturabpa = '$this->tf12_faturabpa' ";
       $virgula = ",";
     }
     if(trim($this->tf12_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_descricao"])){ 
       $sql  .= $virgula." tf12_descricao = '$this->tf12_descricao' ";
       $virgula = ",";
     }
     if(trim($this->tf12_acompanhente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf12_acompanhente"])){ 
       $sql  .= $virgula." tf12_acompanhente = '$this->tf12_acompanhente' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tf12_i_codigo!=null){
       $sql .= " tf12_i_codigo = $this->tf12_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf12_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16373,'$this->tf12_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_i_codigo"]) || $this->tf12_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2868,16373,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_i_codigo'))."','$this->tf12_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_i_procedimento"]) || $this->tf12_i_procedimento != "")
             $resac = db_query("insert into db_acount values($acount,2868,16374,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_i_procedimento'))."','$this->tf12_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_f_valor"]) || $this->tf12_f_valor != "")
             $resac = db_query("insert into db_acount values($acount,2868,16375,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_f_valor'))."','$this->tf12_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadeini"]) || $this->tf12_d_validadeini != "")
             $resac = db_query("insert into db_acount values($acount,2868,16377,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_d_validadeini'))."','$this->tf12_d_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_d_validadefim"]) || $this->tf12_d_validadefim != "")
             $resac = db_query("insert into db_acount values($acount,2868,16378,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_d_validadefim'))."','$this->tf12_d_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_faturabpa"]) || $this->tf12_faturabpa != "")
             $resac = db_query("insert into db_acount values($acount,2868,18274,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_faturabpa'))."','$this->tf12_faturabpa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_descricao"]) || $this->tf12_descricao != "")
             $resac = db_query("insert into db_acount values($acount,2868,18273,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_descricao'))."','$this->tf12_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf12_acompanhente"]) || $this->tf12_acompanhente != "")
             $resac = db_query("insert into db_acount values($acount,2868,20337,'".AddSlashes(pg_result($resaco,$conresaco,'tf12_acompanhente'))."','$this->tf12_acompanhente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_ajudacusto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_ajudacusto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf12_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tf12_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16373,'$tf12_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2868,16373,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,16374,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,16375,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,16377,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,16378,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,18274,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_faturabpa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,18273,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2868,20337,'','".AddSlashes(pg_result($resaco,$iresaco,'tf12_acompanhente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from tfd_ajudacusto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf12_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf12_i_codigo = $tf12_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_ajudacusto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_ajudacusto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf12_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_ajudacusto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_ajudacusto ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = tfd_ajudacusto.tf12_i_procedimento";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left  join sau_rubrica  on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      inner join sau_complexidade  on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($tf12_i_codigo!=null ){
         $sql2 .= " where tfd_ajudacusto.tf12_i_codigo = $tf12_i_codigo "; 
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
   function sql_query_file ( $tf12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_ajudacusto ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf12_i_codigo!=null ){
         $sql2 .= " where tfd_ajudacusto.tf12_i_codigo = $tf12_i_codigo "; 
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
   function sql_query2 ( $tf12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_ajudacusto ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = tfd_ajudacusto.tf12_i_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($tf12_i_codigo!=null ){
         $sql2 .= " where tfd_ajudacusto.tf12_i_codigo = $tf12_i_codigo "; 
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
   function sql_query_valor_unitario($tf12_i_codigo=null,$campos="*",
                                     $ordem='tf12_i_codigo, a.sd63_c_procedimento, 
                                     a.sd63_i_anocomp desc, a.sd63_i_mescomp desc',$dbwhere="") { 
     $sql = "select distinct on (tf12_i_codigo) ";
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
     $sql .= " from tfd_ajudacusto ";
     $sql .= "      inner join sau_procedimento as a on  a.sd63_i_codigo = tfd_ajudacusto.tf12_i_procedimento";
     $sql .= "      inner join sau_procedimento on  a.sd63_c_procedimento = sau_procedimento.sd63_c_procedimento";
     $sql2 = "";
     if($dbwhere==""){
       if($tf12_i_codigo!=null ){
         $sql2 .= " where tfd_ajudacusto.tf12_i_codigo = $tf12_i_codigo "; 
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