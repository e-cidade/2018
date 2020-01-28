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
//CLASSE DA ENTIDADE sau_upsparalisada
class cl_sau_upsparalisada { 
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
   var $s140_i_codigo = 0; 
   var $s140_i_unidade = 0; 
   var $s140_d_inicio_dia = null; 
   var $s140_d_inicio_mes = null; 
   var $s140_d_inicio_ano = null; 
   var $s140_d_inicio = null; 
   var $s140_d_fim_dia = null; 
   var $s140_d_fim_mes = null; 
   var $s140_d_fim_ano = null; 
   var $s140_d_fim = null; 
   var $s140_i_tipo = 0; 
   var $s140_c_horaini = null; 
   var $s140_c_horafim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s140_i_codigo = int4 = C�digo 
                 s140_i_unidade = int4 = Unidade 
                 s140_d_inicio = date = In�cio 
                 s140_d_fim = date = Fim 
                 s140_i_tipo = int4 = Motivo da paralisa��o 
                 s140_c_horaini = char(5) = Hora inicial 
                 s140_c_horafim = char(5) = Hora final 
                 ";
   //funcao construtor da classe 
   function cl_sau_upsparalisada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_upsparalisada"); 
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
       $this->s140_i_codigo = ($this->s140_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_i_codigo"]:$this->s140_i_codigo);
       $this->s140_i_unidade = ($this->s140_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_i_unidade"]:$this->s140_i_unidade);
       if($this->s140_d_inicio == ""){
         $this->s140_d_inicio_dia = ($this->s140_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_dia"]:$this->s140_d_inicio_dia);
         $this->s140_d_inicio_mes = ($this->s140_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_mes"]:$this->s140_d_inicio_mes);
         $this->s140_d_inicio_ano = ($this->s140_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_ano"]:$this->s140_d_inicio_ano);
         if($this->s140_d_inicio_dia != ""){
            $this->s140_d_inicio = $this->s140_d_inicio_ano."-".$this->s140_d_inicio_mes."-".$this->s140_d_inicio_dia;
         }
       }
       if($this->s140_d_fim == ""){
         $this->s140_d_fim_dia = ($this->s140_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_fim_dia"]:$this->s140_d_fim_dia);
         $this->s140_d_fim_mes = ($this->s140_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_fim_mes"]:$this->s140_d_fim_mes);
         $this->s140_d_fim_ano = ($this->s140_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_d_fim_ano"]:$this->s140_d_fim_ano);
         if($this->s140_d_fim_dia != ""){
            $this->s140_d_fim = $this->s140_d_fim_ano."-".$this->s140_d_fim_mes."-".$this->s140_d_fim_dia;
         }
       }
       $this->s140_i_tipo = ($this->s140_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_i_tipo"]:$this->s140_i_tipo);
       $this->s140_c_horaini = ($this->s140_c_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_c_horaini"]:$this->s140_c_horaini);
       $this->s140_c_horafim = ($this->s140_c_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_c_horafim"]:$this->s140_c_horafim);
     }else{
       $this->s140_i_codigo = ($this->s140_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s140_i_codigo"]:$this->s140_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s140_i_codigo){ 
      $this->atualizacampos();
     if($this->s140_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "s140_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s140_d_inicio == null ){ 
       $this->erro_sql = " Campo In�cio nao Informado.";
       $this->erro_campo = "s140_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s140_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "s140_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s140_i_tipo == null ){ 
       $this->erro_sql = " Campo Motivo da paralisa��o nao Informado.";
       $this->erro_campo = "s140_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s140_i_codigo == "" || $s140_i_codigo == null ){
       $result = db_query("select nextval('sau_upsparalisada_s140_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_upsparalisada_s140_codigo_seq do campo: s140_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s140_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_upsparalisada_s140_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s140_i_codigo)){
         $this->erro_sql = " Campo s140_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s140_i_codigo = $s140_i_codigo; 
       }
     }
     if(($this->s140_i_codigo == null) || ($this->s140_i_codigo == "") ){ 
       $this->erro_sql = " Campo s140_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_upsparalisada(
                                       s140_i_codigo 
                                      ,s140_i_unidade 
                                      ,s140_d_inicio 
                                      ,s140_d_fim 
                                      ,s140_i_tipo 
                                      ,s140_c_horaini 
                                      ,s140_c_horafim 
                       )
                values (
                                $this->s140_i_codigo 
                               ,$this->s140_i_unidade 
                               ,".($this->s140_d_inicio == "null" || $this->s140_d_inicio == ""?"null":"'".$this->s140_d_inicio."'")." 
                               ,".($this->s140_d_fim == "null" || $this->s140_d_fim == ""?"null":"'".$this->s140_d_fim."'")." 
                               ,$this->s140_i_tipo 
                               ,'$this->s140_c_horaini' 
                               ,'$this->s140_c_horafim' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sau_upsparalisada ($this->s140_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sau_upsparalisada j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sau_upsparalisada ($this->s140_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s140_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s140_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15330,'$this->s140_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2699,15330,'','".AddSlashes(pg_result($resaco,0,'s140_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,15331,'','".AddSlashes(pg_result($resaco,0,'s140_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,15332,'','".AddSlashes(pg_result($resaco,0,'s140_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,15333,'','".AddSlashes(pg_result($resaco,0,'s140_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,15334,'','".AddSlashes(pg_result($resaco,0,'s140_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,17150,'','".AddSlashes(pg_result($resaco,0,'s140_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2699,17151,'','".AddSlashes(pg_result($resaco,0,'s140_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s140_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_upsparalisada set ";
     $virgula = "";
     if(trim($this->s140_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_i_codigo"])){ 
       $sql  .= $virgula." s140_i_codigo = $this->s140_i_codigo ";
       $virgula = ",";
       if(trim($this->s140_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "s140_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s140_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_i_unidade"])){ 
       $sql  .= $virgula." s140_i_unidade = $this->s140_i_unidade ";
       $virgula = ",";
       if(trim($this->s140_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "s140_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s140_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." s140_d_inicio = '$this->s140_d_inicio' ";
       $virgula = ",";
       if(trim($this->s140_d_inicio) == null ){ 
         $this->erro_sql = " Campo In�cio nao Informado.";
         $this->erro_campo = "s140_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s140_d_inicio_dia"])){ 
         $sql  .= $virgula." s140_d_inicio = null ";
         $virgula = ",";
         if(trim($this->s140_d_inicio) == null ){ 
           $this->erro_sql = " Campo In�cio nao Informado.";
           $this->erro_campo = "s140_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s140_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["s140_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." s140_d_fim = '$this->s140_d_fim' ";
       $virgula = ",";
       if(trim($this->s140_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "s140_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["s140_d_fim_dia"])){ 
         $sql  .= $virgula." s140_d_fim = null ";
         $virgula = ",";
         if(trim($this->s140_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "s140_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->s140_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_i_tipo"])){ 
       $sql  .= $virgula." s140_i_tipo = $this->s140_i_tipo ";
       $virgula = ",";
       if(trim($this->s140_i_tipo) == null ){ 
         $this->erro_sql = " Campo Motivo da paralisa��o nao Informado.";
         $this->erro_campo = "s140_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s140_c_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_c_horaini"])){ 
       $sql  .= $virgula." s140_c_horaini = '$this->s140_c_horaini' ";
       $virgula = ",";
     }
     if(trim($this->s140_c_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s140_c_horafim"])){ 
       $sql  .= $virgula." s140_c_horafim = '$this->s140_c_horafim' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($s140_i_codigo!=null){
       $sql .= " s140_i_codigo = $this->s140_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s140_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15330,'$this->s140_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_i_codigo"]) || $this->s140_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2699,15330,'".AddSlashes(pg_result($resaco,$conresaco,'s140_i_codigo'))."','$this->s140_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_i_unidade"]) || $this->s140_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2699,15331,'".AddSlashes(pg_result($resaco,$conresaco,'s140_i_unidade'))."','$this->s140_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_d_inicio"]) || $this->s140_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,2699,15332,'".AddSlashes(pg_result($resaco,$conresaco,'s140_d_inicio'))."','$this->s140_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_d_fim"]) || $this->s140_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2699,15333,'".AddSlashes(pg_result($resaco,$conresaco,'s140_d_fim'))."','$this->s140_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_i_tipo"]) || $this->s140_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2699,15334,'".AddSlashes(pg_result($resaco,$conresaco,'s140_i_tipo'))."','$this->s140_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_c_horaini"]) || $this->s140_c_horaini != "")
           $resac = db_query("insert into db_acount values($acount,2699,17150,'".AddSlashes(pg_result($resaco,$conresaco,'s140_c_horaini'))."','$this->s140_c_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s140_c_horafim"]) || $this->s140_c_horafim != "")
           $resac = db_query("insert into db_acount values($acount,2699,17151,'".AddSlashes(pg_result($resaco,$conresaco,'s140_c_horafim'))."','$this->s140_c_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_upsparalisada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s140_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_upsparalisada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s140_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s140_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s140_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s140_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15330,'$s140_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2699,15330,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,15331,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,15332,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,15333,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,15334,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,17150,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_c_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2699,17151,'','".AddSlashes(pg_result($resaco,$iresaco,'s140_c_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_upsparalisada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s140_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s140_i_codigo = $s140_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sau_upsparalisada nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s140_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "sau_upsparalisada nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s140_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s140_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:sau_upsparalisada";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s140_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_upsparalisada ";
     $sql .= "      inner join sau_motivo_ausencia  on  sau_motivo_ausencia.s139_i_codigo = sau_upsparalisada.s140_i_tipo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = sau_upsparalisada.s140_i_unidade";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm and  cgm.z01_numcgm = unidades.sd02_i_diretor";
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
       if($s140_i_codigo!=null ){
         $sql2 .= " where sau_upsparalisada.s140_i_codigo = $s140_i_codigo "; 
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
   function sql_query_file ( $s140_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_upsparalisada ";
     $sql2 = "";
     if($dbwhere==""){
       if($s140_i_codigo!=null ){
         $sql2 .= " where sau_upsparalisada.s140_i_codigo = $s140_i_codigo "; 
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