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

//MODULO: educação
//CLASSE DA ENTIDADE periodocalendario
class cl_periodocalendario { 
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
   var $ed53_i_codigo = 0; 
   var $ed53_i_calendario = 0; 
   var $ed53_i_periodoavaliacao = 0; 
   var $ed53_d_inicio_dia = null; 
   var $ed53_d_inicio_mes = null; 
   var $ed53_d_inicio_ano = null; 
   var $ed53_d_inicio = null; 
   var $ed53_d_fim_dia = null; 
   var $ed53_d_fim_mes = null; 
   var $ed53_d_fim_ano = null; 
   var $ed53_d_fim = null; 
   var $ed53_i_diasletivos = 0; 
   var $ed53_i_semletivas = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed53_i_codigo = int8 = Código 
                 ed53_i_calendario = int8 = Calendário 
                 ed53_i_periodoavaliacao = int8 = Período de Avaliação 
                 ed53_d_inicio = date = Data Inicial 
                 ed53_d_fim = date = Data Final 
                 ed53_i_diasletivos = int4 = Dias Letivos 
                 ed53_i_semletivas = int4 = Semanas Letivas 
                 ";
   //funcao construtor da classe 
   function cl_periodocalendario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("periodocalendario"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed53_i_calendario=".@$GLOBALS["HTTP_POST_VARS"]["ed53_i_calendario"]."&ed52_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed52_c_descr"]);
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
       $this->ed53_i_codigo = ($this->ed53_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_codigo"]:$this->ed53_i_codigo);
       $this->ed53_i_calendario = ($this->ed53_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_calendario"]:$this->ed53_i_calendario);
       $this->ed53_i_periodoavaliacao = ($this->ed53_i_periodoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_periodoavaliacao"]:$this->ed53_i_periodoavaliacao);
       if($this->ed53_d_inicio == ""){
         $this->ed53_d_inicio_dia = ($this->ed53_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_dia"]:$this->ed53_d_inicio_dia);
         $this->ed53_d_inicio_mes = ($this->ed53_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_mes"]:$this->ed53_d_inicio_mes);
         $this->ed53_d_inicio_ano = ($this->ed53_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_ano"]:$this->ed53_d_inicio_ano);
         if($this->ed53_d_inicio_dia != ""){
            $this->ed53_d_inicio = $this->ed53_d_inicio_ano."-".$this->ed53_d_inicio_mes."-".$this->ed53_d_inicio_dia;
         }
       }
       if($this->ed53_d_fim == ""){
         $this->ed53_d_fim_dia = ($this->ed53_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_dia"]:$this->ed53_d_fim_dia);
         $this->ed53_d_fim_mes = ($this->ed53_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_mes"]:$this->ed53_d_fim_mes);
         $this->ed53_d_fim_ano = ($this->ed53_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_ano"]:$this->ed53_d_fim_ano);
         if($this->ed53_d_fim_dia != ""){
            $this->ed53_d_fim = $this->ed53_d_fim_ano."-".$this->ed53_d_fim_mes."-".$this->ed53_d_fim_dia;
         }
       }
       $this->ed53_i_diasletivos = ($this->ed53_i_diasletivos == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_diasletivos"]:$this->ed53_i_diasletivos);
       $this->ed53_i_semletivas = ($this->ed53_i_semletivas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_semletivas"]:$this->ed53_i_semletivas);
     }else{
       $this->ed53_i_codigo = ($this->ed53_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed53_i_codigo"]:$this->ed53_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed53_i_codigo){ 
      $this->atualizacampos();
     if($this->ed53_i_calendario == null ){ 
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed53_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed53_i_periodoavaliacao == null ){ 
       $this->erro_sql = " Campo Período de Avaliação nao Informado.";
       $this->erro_campo = "ed53_i_periodoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed53_d_inicio == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ed53_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed53_d_fim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ed53_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed53_i_diasletivos == null ){ 
       $this->ed53_i_diasletivos = "0";
     }
     if($this->ed53_i_semletivas == null ){ 
       $this->ed53_i_semletivas = "0";
     }
     if($ed53_i_codigo == "" || $ed53_i_codigo == null ){
       $result = db_query("select nextval('periodocalendario_ed53_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: periodocalendario_ed53_i_codigo_seq do campo: ed53_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed53_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from periodocalendario_ed53_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed53_i_codigo)){
         $this->erro_sql = " Campo ed53_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed53_i_codigo = $ed53_i_codigo; 
       }
     }
     if(($this->ed53_i_codigo == null) || ($this->ed53_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed53_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into periodocalendario(
                                       ed53_i_codigo 
                                      ,ed53_i_calendario 
                                      ,ed53_i_periodoavaliacao 
                                      ,ed53_d_inicio 
                                      ,ed53_d_fim 
                                      ,ed53_i_diasletivos 
                                      ,ed53_i_semletivas 
                       )
                values (
                                $this->ed53_i_codigo 
                               ,$this->ed53_i_calendario 
                               ,$this->ed53_i_periodoavaliacao 
                               ,".($this->ed53_d_inicio == "null" || $this->ed53_d_inicio == ""?"null":"'".$this->ed53_d_inicio."'")." 
                               ,".($this->ed53_d_fim == "null" || $this->ed53_d_fim == ""?"null":"'".$this->ed53_d_fim."'")." 
                               ,$this->ed53_i_diasletivos 
                               ,$this->ed53_i_semletivas 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Períodos do Calendário Escolar ($this->ed53_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Períodos do Calendário Escolar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Períodos do Calendário Escolar ($this->ed53_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed53_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed53_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008346,'$this->ed53_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010059,1008346,'','".AddSlashes(pg_result($resaco,0,'ed53_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008347,'','".AddSlashes(pg_result($resaco,0,'ed53_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008348,'','".AddSlashes(pg_result($resaco,0,'ed53_i_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008349,'','".AddSlashes(pg_result($resaco,0,'ed53_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008350,'','".AddSlashes(pg_result($resaco,0,'ed53_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008351,'','".AddSlashes(pg_result($resaco,0,'ed53_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010059,1008352,'','".AddSlashes(pg_result($resaco,0,'ed53_i_semletivas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed53_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update periodocalendario set ";
     $virgula = "";
     if(trim($this->ed53_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_codigo"])){ 
       $sql  .= $virgula." ed53_i_codigo = $this->ed53_i_codigo ";
       $virgula = ",";
       if(trim($this->ed53_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed53_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed53_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_calendario"])){ 
       $sql  .= $virgula." ed53_i_calendario = $this->ed53_i_calendario ";
       $virgula = ",";
       if(trim($this->ed53_i_calendario) == null ){ 
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed53_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed53_i_periodoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_periodoavaliacao"])){ 
       $sql  .= $virgula." ed53_i_periodoavaliacao = $this->ed53_i_periodoavaliacao ";
       $virgula = ",";
       if(trim($this->ed53_i_periodoavaliacao) == null ){ 
         $this->erro_sql = " Campo Período de Avaliação nao Informado.";
         $this->erro_campo = "ed53_i_periodoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed53_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed53_d_inicio = '$this->ed53_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed53_d_inicio) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ed53_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio_dia"])){ 
         $sql  .= $virgula." ed53_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed53_d_inicio) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ed53_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed53_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." ed53_d_fim = '$this->ed53_d_fim' ";
       $virgula = ",";
       if(trim($this->ed53_d_fim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ed53_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_fim_dia"])){ 
         $sql  .= $virgula." ed53_d_fim = null ";
         $virgula = ",";
         if(trim($this->ed53_d_fim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ed53_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed53_i_diasletivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_diasletivos"])){ 
        if(trim($this->ed53_i_diasletivos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_diasletivos"])){ 
           $this->ed53_i_diasletivos = "0" ; 
        } 
       $sql  .= $virgula." ed53_i_diasletivos = $this->ed53_i_diasletivos ";
       $virgula = ",";
     }
     if(trim($this->ed53_i_semletivas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_semletivas"])){ 
        if(trim($this->ed53_i_semletivas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_semletivas"])){ 
           $this->ed53_i_semletivas = "0" ; 
        } 
       $sql  .= $virgula." ed53_i_semletivas = $this->ed53_i_semletivas ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed53_i_codigo!=null){
       $sql .= " ed53_i_codigo = $this->ed53_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed53_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008346,'$this->ed53_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008346,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_i_codigo'))."','$this->ed53_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_calendario"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008347,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_i_calendario'))."','$this->ed53_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_periodoavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008348,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_i_periodoavaliacao'))."','$this->ed53_i_periodoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_inicio"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008349,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_d_inicio'))."','$this->ed53_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_d_fim"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008350,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_d_fim'))."','$this->ed53_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_diasletivos"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008351,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_i_diasletivos'))."','$this->ed53_i_diasletivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed53_i_semletivas"]))
           $resac = db_query("insert into db_acount values($acount,1010059,1008352,'".AddSlashes(pg_result($resaco,$conresaco,'ed53_i_semletivas'))."','$this->ed53_i_semletivas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos do Calendário Escolar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed53_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos do Calendário Escolar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed53_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed53_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed53_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed53_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008346,'$ed53_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010059,1008346,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008347,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008348,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_i_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008349,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008350,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008351,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_i_diasletivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010059,1008352,'','".AddSlashes(pg_result($resaco,$iresaco,'ed53_i_semletivas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from periodocalendario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed53_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed53_i_codigo = $ed53_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos do Calendário Escolar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed53_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos do Calendário Escolar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed53_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed53_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:periodocalendario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed53_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodocalendario ";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = periodocalendario.ed53_i_periodoavaliacao";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = periodocalendario.ed53_i_calendario";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql2 = "";
     if($dbwhere==""){
       if($ed53_i_codigo!=null ){
         $sql2 .= " where periodocalendario.ed53_i_codigo = $ed53_i_codigo "; 
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
   function sql_query_file ( $ed53_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodocalendario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed53_i_codigo!=null ){
         $sql2 .= " where periodocalendario.ed53_i_codigo = $ed53_i_codigo "; 
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
  
  function sql_query_escola ( $ed53_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from periodocalendario ";
    $sql .= "      inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = periodocalendario.ed53_i_periodoavaliacao";
    $sql .= "      inner join calendario       on calendario.ed52_i_codigo = periodocalendario.ed53_i_calendario";
    $sql .= "      inner join calendarioescola on calendarioescola.ed38_i_calendario =  calendario.ed52_i_codigo";
    $sql .= "      inner join duracaocal       on duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
    $sql2 = "";
    if ($dbwhere == "") {
      
      if ($ed53_i_codigo != null) {
        
        $sql2 .= " where periodocalendario.ed53_i_codigo = $ed53_i_codigo ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>