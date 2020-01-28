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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_subitem
class cl_mer_subitem { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me29_i_codigo        = 0; 
   var $me29_i_refeicao        = 0; 
   var $me29_i_alimentonovo        = 0; 
   var $me29_i_alimentoorig        = 0; 
   var $me29_f_quantidade        = 0; 
   var $me29_c_medidacaseira        = null; 
   var $me29_d_inicio_dia    = null; 
   var $me29_d_inicio_mes    = null; 
   var $me29_d_inicio_ano    = null; 
   var $me29_d_inicio        = null; 
   var $me29_d_fim_dia    = null; 
   var $me29_d_fim_mes    = null; 
   var $me29_d_fim_ano    = null; 
   var $me29_d_fim        = null; 
   var $me29_t_obs        = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me29_i_codigo = int4 = código 
                 me29_i_refeicao = int4 = Refeição 
                 me29_i_alimentonovo = int4 = Alimento Substituído 
                 me29_i_alimentoorig = int4 = Alimento 
                 me29_f_quantidade = float4 = Quantidade 
                 me29_c_medidacaseira = char(150) = Medida caseira 
                 me29_d_inicio = date = Data Inicio 
                 me29_d_fim = date = data final 
                 me29_t_obs = text = Justificativa 
                 ";
   //funcao construtor da classe 
   function cl_mer_subitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_subitem"); 
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
       $this->me29_i_codigo = ($this->me29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_i_codigo"]:$this->me29_i_codigo);
       $this->me29_i_refeicao = ($this->me29_i_refeicao == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_i_refeicao"]:$this->me29_i_refeicao);
       $this->me29_i_alimentonovo = ($this->me29_i_alimentonovo == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_i_alimentonovo"]:$this->me29_i_alimentonovo);
       $this->me29_i_alimentoorig = ($this->me29_i_alimentoorig == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_i_alimentoorig"]:$this->me29_i_alimentoorig);
       $this->me29_f_quantidade = ($this->me29_f_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_f_quantidade"]:$this->me29_f_quantidade);
       $this->me29_c_medidacaseira = ($this->me29_c_medidacaseira == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_c_medidacaseira"]:$this->me29_c_medidacaseira);
       if($this->me29_d_inicio == ""){
         $this->me29_d_inicio_dia = ($this->me29_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_dia"]:$this->me29_d_inicio_dia);
         $this->me29_d_inicio_mes = ($this->me29_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_mes"]:$this->me29_d_inicio_mes);
         $this->me29_d_inicio_ano = ($this->me29_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_ano"]:$this->me29_d_inicio_ano);
         if($this->me29_d_inicio_dia != ""){
            $this->me29_d_inicio = $this->me29_d_inicio_ano."-".$this->me29_d_inicio_mes."-".$this->me29_d_inicio_dia;
         }
       }
       if($this->me29_d_fim == ""){
         $this->me29_d_fim_dia = ($this->me29_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_fim_dia"]:$this->me29_d_fim_dia);
         $this->me29_d_fim_mes = ($this->me29_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_fim_mes"]:$this->me29_d_fim_mes);
         $this->me29_d_fim_ano = ($this->me29_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_d_fim_ano"]:$this->me29_d_fim_ano);
         if($this->me29_d_fim_dia != ""){
            $this->me29_d_fim = $this->me29_d_fim_ano."-".$this->me29_d_fim_mes."-".$this->me29_d_fim_dia;
         }
       }
       $this->me29_t_obs = ($this->me29_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_t_obs"]:$this->me29_t_obs);
     }else{
       $this->me29_i_codigo = ($this->me29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me29_i_codigo"]:$this->me29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me29_i_codigo){ 
      $this->atualizacampos();
     if($this->me29_i_refeicao == null ){ 
       $this->erro_sql = " Campo Refeição nao Informado.";
       $this->erro_campo = "me29_i_refeicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_i_alimentonovo == null ){ 
       $this->erro_sql = " Campo Alimento Substituído nao Informado.";
       $this->erro_campo = "me29_i_alimentonovo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_i_alimentoorig == null ){ 
       $this->erro_sql = " Campo Alimento nao Informado.";
       $this->erro_campo = "me29_i_alimentoorig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_f_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me29_f_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_c_medidacaseira == null ){ 
       $this->erro_sql = " Campo Medida caseira nao Informado.";
       $this->erro_campo = "me29_c_medidacaseira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_d_inicio == null ){ 
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "me29_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_d_fim == null ){ 
       $this->erro_sql = " Campo data final nao Informado.";
       $this->erro_campo = "me29_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me29_t_obs == null ){ 
       $this->erro_sql = " Campo Justificativa nao Informado.";
       $this->erro_campo = "me29_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me29_i_codigo == "" || $me29_i_codigo == null ){
       $result = db_query("select nextval('mer_subitem_me29_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_subitem_me29_codigo_seq do campo: me29_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me29_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_subitem_me29_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me29_i_codigo)){
         $this->erro_sql = " Campo me29_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me29_i_codigo = $me29_i_codigo; 
       }
     }
     if(($this->me29_i_codigo == null) || ($this->me29_i_codigo == "") ){ 
       $this->erro_sql = " Campo me29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_subitem(
                                       me29_i_codigo 
                                      ,me29_i_refeicao 
                                      ,me29_i_alimentonovo 
                                      ,me29_i_alimentoorig 
                                      ,me29_f_quantidade 
                                      ,me29_c_medidacaseira 
                                      ,me29_d_inicio 
                                      ,me29_d_fim 
                                      ,me29_t_obs 
                       )
                values (
                                $this->me29_i_codigo 
                               ,$this->me29_i_refeicao 
                               ,$this->me29_i_alimentonovo 
                               ,$this->me29_i_alimentoorig 
                               ,$this->me29_f_quantidade 
                               ,'$this->me29_c_medidacaseira' 
                               ,".($this->me29_d_inicio == "null" || $this->me29_d_inicio == ""?"null":"'".$this->me29_d_inicio."'")." 
                               ,".($this->me29_d_fim == "null" || $this->me29_d_fim == ""?"null":"'".$this->me29_d_fim."'")." 
                               ,'$this->me29_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "substituição de itens ($this->me29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "substituição de itens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "substituição de itens ($this->me29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me29_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14462,'$this->me29_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2549,14462,'','".AddSlashes(pg_result($resaco,0,'me29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14463,'','".AddSlashes(pg_result($resaco,0,'me29_i_refeicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14464,'','".AddSlashes(pg_result($resaco,0,'me29_i_alimentonovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14465,'','".AddSlashes(pg_result($resaco,0,'me29_i_alimentoorig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14466,'','".AddSlashes(pg_result($resaco,0,'me29_f_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14467,'','".AddSlashes(pg_result($resaco,0,'me29_c_medidacaseira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14468,'','".AddSlashes(pg_result($resaco,0,'me29_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14469,'','".AddSlashes(pg_result($resaco,0,'me29_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2549,14470,'','".AddSlashes(pg_result($resaco,0,'me29_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_subitem set ";
     $virgula = "";
     if(trim($this->me29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_i_codigo"])){ 
       $sql  .= $virgula." me29_i_codigo = $this->me29_i_codigo ";
       $virgula = ",";
       if(trim($this->me29_i_codigo) == null ){ 
         $this->erro_sql = " Campo código nao Informado.";
         $this->erro_campo = "me29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_i_refeicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_i_refeicao"])){ 
       $sql  .= $virgula." me29_i_refeicao = $this->me29_i_refeicao ";
       $virgula = ",";
       if(trim($this->me29_i_refeicao) == null ){ 
         $this->erro_sql = " Campo Refeição nao Informado.";
         $this->erro_campo = "me29_i_refeicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_i_alimentonovo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_i_alimentonovo"])){ 
       $sql  .= $virgula." me29_i_alimentonovo = $this->me29_i_alimentonovo ";
       $virgula = ",";
       if(trim($this->me29_i_alimentonovo) == null ){ 
         $this->erro_sql = " Campo Alimento Substituído nao Informado.";
         $this->erro_campo = "me29_i_alimentonovo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_i_alimentoorig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_i_alimentoorig"])){ 
       $sql  .= $virgula." me29_i_alimentoorig = $this->me29_i_alimentoorig ";
       $virgula = ",";
       if(trim($this->me29_i_alimentoorig) == null ){ 
         $this->erro_sql = " Campo Alimento nao Informado.";
         $this->erro_campo = "me29_i_alimentoorig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_f_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_f_quantidade"])){ 
       $sql  .= $virgula." me29_f_quantidade = $this->me29_f_quantidade ";
       $virgula = ",";
       if(trim($this->me29_f_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me29_f_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_c_medidacaseira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_c_medidacaseira"])){ 
       $sql  .= $virgula." me29_c_medidacaseira = '$this->me29_c_medidacaseira' ";
       $virgula = ",";
       if(trim($this->me29_c_medidacaseira) == null ){ 
         $this->erro_sql = " Campo Medida caseira nao Informado.";
         $this->erro_campo = "me29_c_medidacaseira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me29_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." me29_d_inicio = '$this->me29_d_inicio' ";
       $virgula = ",";
       if(trim($this->me29_d_inicio) == null ){ 
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "me29_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me29_d_inicio_dia"])){ 
         $sql  .= $virgula." me29_d_inicio = null ";
         $virgula = ",";
         if(trim($this->me29_d_inicio) == null ){ 
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "me29_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me29_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me29_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." me29_d_fim = '$this->me29_d_fim' ";
       $virgula = ",";
       if(trim($this->me29_d_fim) == null ){ 
         $this->erro_sql = " Campo data final nao Informado.";
         $this->erro_campo = "me29_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me29_d_fim_dia"])){ 
         $sql  .= $virgula." me29_d_fim = null ";
         $virgula = ",";
         if(trim($this->me29_d_fim) == null ){ 
           $this->erro_sql = " Campo data final nao Informado.";
           $this->erro_campo = "me29_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->me29_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me29_t_obs"])){ 
       $sql  .= $virgula." me29_t_obs = '$this->me29_t_obs' ";
       $virgula = ",";
       if(trim($this->me29_t_obs) == null ){ 
         $this->erro_sql = " Campo Justificativa nao Informado.";
         $this->erro_campo = "me29_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me29_i_codigo!=null){
       $sql .= " me29_i_codigo = $this->me29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14462,'$this->me29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_i_codigo"]) || $this->me29_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2549,14462,'".AddSlashes(pg_result($resaco,$conresaco,'me29_i_codigo'))."','$this->me29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_i_refeicao"]) || $this->me29_i_refeicao != "")
           $resac = db_query("insert into db_acount values($acount,2549,14463,'".AddSlashes(pg_result($resaco,$conresaco,'me29_i_refeicao'))."','$this->me29_i_refeicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_i_alimentonovo"]) || $this->me29_i_alimentonovo != "")
           $resac = db_query("insert into db_acount values($acount,2549,14464,'".AddSlashes(pg_result($resaco,$conresaco,'me29_i_alimentonovo'))."','$this->me29_i_alimentonovo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_i_alimentoorig"]) || $this->me29_i_alimentoorig != "")
           $resac = db_query("insert into db_acount values($acount,2549,14465,'".AddSlashes(pg_result($resaco,$conresaco,'me29_i_alimentoorig'))."','$this->me29_i_alimentoorig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_f_quantidade"]) || $this->me29_f_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2549,14466,'".AddSlashes(pg_result($resaco,$conresaco,'me29_f_quantidade'))."','$this->me29_f_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_c_medidacaseira"]) || $this->me29_c_medidacaseira != "")
           $resac = db_query("insert into db_acount values($acount,2549,14467,'".AddSlashes(pg_result($resaco,$conresaco,'me29_c_medidacaseira'))."','$this->me29_c_medidacaseira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_d_inicio"]) || $this->me29_d_inicio != "")
           $resac = db_query("insert into db_acount values($acount,2549,14468,'".AddSlashes(pg_result($resaco,$conresaco,'me29_d_inicio'))."','$this->me29_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_d_fim"]) || $this->me29_d_fim != "")
           $resac = db_query("insert into db_acount values($acount,2549,14469,'".AddSlashes(pg_result($resaco,$conresaco,'me29_d_fim'))."','$this->me29_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me29_t_obs"]) || $this->me29_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2549,14470,'".AddSlashes(pg_result($resaco,$conresaco,'me29_t_obs'))."','$this->me29_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "substituição de itens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "substituição de itens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14462,'$me29_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2549,14462,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14463,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_i_refeicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14464,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_i_alimentonovo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14465,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_i_alimentoorig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14466,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_f_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14467,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_c_medidacaseira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14468,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14469,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2549,14470,'','".AddSlashes(pg_result($resaco,$iresaco,'me29_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_subitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me29_i_codigo = $me29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "substituição de itens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "substituição de itens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_subitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_subitem ";
     $sql .= "      left join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_subitem.me29_i_refeicao";
     $sql .= "      left join mer_alimento  on  mer_alimento.me35_i_codigo = mer_subitem.me29_i_alimentonovo";
     $sql .= "      left join mer_alimento as alimento on alimento.me35_i_codigo = mer_subitem.me29_i_alimentoorig";
     $sql .= "      left join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql .= "      left join matunid  on  matunid.m61_codmatunid = mer_alimento.me35_i_unidade";
     $sql .= "      left join mer_grupoalimento  on  mer_grupoalimento.me30_i_codigo = mer_alimento.me35_i_grupoalimentar";
     $sql2 = "";
     if($dbwhere==""){
       if($me29_i_codigo!=null ){
         $sql2 .= " where mer_subitem.me29_i_codigo = $me29_i_codigo "; 
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
   function sql_query_file ( $me29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_subitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($me29_i_codigo!=null ){
         $sql2 .= " where mer_subitem.me29_i_codigo = $me29_i_codigo "; 
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