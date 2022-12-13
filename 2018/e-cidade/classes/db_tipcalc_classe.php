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

//MODULO: issqn
//CLASSE DA ENTIDADE tipcalc
class cl_tipcalc { 
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
   var $q81_codigo = 0; 
   var $q81_descr = null; 
   var $q81_abrev = null; 
   var $q81_cadcalc = 0; 
   var $q81_integr = 'f'; 
   var $q81_tippro = null; 
   var $q81_recexe = 0; 
   var $q81_qiexe = 0; 
   var $q81_qfexe = 0; 
   var $q81_valexe = 0; 
   var $q81_recpro = 0; 
   var $q81_qipro = 0; 
   var $q81_qfpro = 0; 
   var $q81_valpro = 0; 
   var $q81_uqtab = 'f'; 
   var $q81_uqcad = 'f'; 
   var $q81_gera = 0; 
   var $q81_tipo = 0; 
   var $q81_percprovis = 0; 
   var $q81_usaretido = 'f'; 
   var $q81_excedenteativ = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q81_codigo = int4 = codigo do tipo de calculo 
                 q81_descr = varchar(200) = descricao 
                 q81_abrev = varchar(40) = abreviatura da descricao pra mostrar nas consultas 
                 q81_cadcalc = int4 = calculo a ser utilizado 
                 q81_integr = bool = se integral ou nao 
                 q81_tippro = varchar(1) = tipo de proporcionalidade 
                 q81_recexe = int4 = receita do exercicio 
                 q81_qiexe = float8 = quantidade inicial do exercicio 
                 q81_qfexe = float8 = quantidade final do exercicio 
                 q81_valexe = float8 = valor utilizado para o exercicio 
                 q81_recpro = int4 = receita do proximo exercicio 
                 q81_qipro = float8 = quantidade inicial do proximo exercicio 
                 q81_qfpro = float8 = quantidade final do proximo exercicio 
                 q81_valpro = float8 = valor utilizado para o proximo exercicio 
                 q81_uqtab = bool = utilizar quantidade da tabela de atividades 
                 q81_uqcad = bool = utiliza quantidade do cadastro 
                 q81_gera = int4 = tipo de geracao 
                 q81_tipo = int4 = Tipo de cálculo 
                 q81_percprovis = float8 = Percentual para provisorio 
                 q81_usaretido = bool = Se usa essa aliquota na lista de retencoes 
                 q81_excedenteativ = float8 = Excedente por atividade 
                 ";
   //funcao construtor da classe 
   function cl_tipcalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipcalc"); 
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
       $this->q81_codigo = ($this->q81_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_codigo"]:$this->q81_codigo);
       $this->q81_descr = ($this->q81_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_descr"]:$this->q81_descr);
       $this->q81_abrev = ($this->q81_abrev == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_abrev"]:$this->q81_abrev);
       $this->q81_cadcalc = ($this->q81_cadcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_cadcalc"]:$this->q81_cadcalc);
       $this->q81_integr = ($this->q81_integr == "f"?@$GLOBALS["HTTP_POST_VARS"]["q81_integr"]:$this->q81_integr);
       $this->q81_tippro = ($this->q81_tippro == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_tippro"]:$this->q81_tippro);
       $this->q81_recexe = ($this->q81_recexe == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_recexe"]:$this->q81_recexe);
       $this->q81_qiexe = ($this->q81_qiexe == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_qiexe"]:$this->q81_qiexe);
       $this->q81_qfexe = ($this->q81_qfexe == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_qfexe"]:$this->q81_qfexe);
       $this->q81_valexe = ($this->q81_valexe == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_valexe"]:$this->q81_valexe);
       $this->q81_recpro = ($this->q81_recpro == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_recpro"]:$this->q81_recpro);
       $this->q81_qipro = ($this->q81_qipro == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_qipro"]:$this->q81_qipro);
       $this->q81_qfpro = ($this->q81_qfpro == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_qfpro"]:$this->q81_qfpro);
       $this->q81_valpro = ($this->q81_valpro == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_valpro"]:$this->q81_valpro);
       $this->q81_uqtab = ($this->q81_uqtab == "f"?@$GLOBALS["HTTP_POST_VARS"]["q81_uqtab"]:$this->q81_uqtab);
       $this->q81_uqcad = ($this->q81_uqcad == "f"?@$GLOBALS["HTTP_POST_VARS"]["q81_uqcad"]:$this->q81_uqcad);
       $this->q81_gera = ($this->q81_gera == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_gera"]:$this->q81_gera);
       $this->q81_tipo = ($this->q81_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_tipo"]:$this->q81_tipo);
       $this->q81_percprovis = ($this->q81_percprovis == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_percprovis"]:$this->q81_percprovis);
       $this->q81_usaretido = ($this->q81_usaretido == "f"?@$GLOBALS["HTTP_POST_VARS"]["q81_usaretido"]:$this->q81_usaretido);
       $this->q81_excedenteativ = ($this->q81_excedenteativ == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_excedenteativ"]:$this->q81_excedenteativ);
     }else{
       $this->q81_codigo = ($this->q81_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q81_codigo"]:$this->q81_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q81_codigo){ 
      $this->atualizacampos();
     if($this->q81_descr == null ){ 
       $this->erro_sql = " Campo descricao nao Informado.";
       $this->erro_campo = "q81_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_abrev == null ){ 
       $this->erro_sql = " Campo abreviatura da descricao pra mostrar nas consultas nao Informado.";
       $this->erro_campo = "q81_abrev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_cadcalc == null ){ 
       $this->erro_sql = " Campo calculo a ser utilizado nao Informado.";
       $this->erro_campo = "q81_cadcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_integr == null ){ 
       $this->erro_sql = " Campo se integral ou nao nao Informado.";
       $this->erro_campo = "q81_integr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_tippro == null ){ 
       $this->erro_sql = " Campo tipo de proporcionalidade nao Informado.";
       $this->erro_campo = "q81_tippro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_recexe == null ){ 
       $this->erro_sql = " Campo receita do exercicio nao Informado.";
       $this->erro_campo = "q81_recexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_qiexe == null ){ 
       $this->erro_sql = " Campo quantidade inicial do exercicio nao Informado.";
       $this->erro_campo = "q81_qiexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_qfexe == null ){ 
       $this->erro_sql = " Campo quantidade final do exercicio nao Informado.";
       $this->erro_campo = "q81_qfexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_valexe == null ){ 
       $this->erro_sql = " Campo valor utilizado para o exercicio nao Informado.";
       $this->erro_campo = "q81_valexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_recpro == null ){ 
       $this->erro_sql = " Campo receita do proximo exercicio nao Informado.";
       $this->erro_campo = "q81_recpro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_qipro == null ){ 
       $this->erro_sql = " Campo quantidade inicial do proximo exercicio nao Informado.";
       $this->erro_campo = "q81_qipro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_qfpro == null ){ 
       $this->erro_sql = " Campo quantidade final do proximo exercicio nao Informado.";
       $this->erro_campo = "q81_qfpro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_valpro == null ){ 
       $this->erro_sql = " Campo valor utilizado para o proximo exercicio nao Informado.";
       $this->erro_campo = "q81_valpro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_uqtab == null ){ 
       $this->erro_sql = " Campo utilizar quantidade da tabela de atividades nao Informado.";
       $this->erro_campo = "q81_uqtab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_uqcad == null ){ 
       $this->erro_sql = " Campo utiliza quantidade do cadastro nao Informado.";
       $this->erro_campo = "q81_uqcad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_gera == null ){ 
       $this->erro_sql = " Campo tipo de geracao nao Informado.";
       $this->erro_campo = "q81_gera";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de cálculo nao Informado.";
       $this->erro_campo = "q81_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_percprovis == null ){ 
       $this->erro_sql = " Campo Percentual para provisorio nao Informado.";
       $this->erro_campo = "q81_percprovis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_usaretido == null ){ 
       $this->erro_sql = " Campo Se usa essa aliquota na lista de retencoes nao Informado.";
       $this->erro_campo = "q81_usaretido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q81_excedenteativ == null ){ 
       $this->erro_sql = " Campo Excedente por atividade nao Informado.";
       $this->erro_campo = "q81_excedenteativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q81_codigo == "" || $q81_codigo == null ){
       $result = db_query("select nextval('tipcalc_q81_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipcalc_q81_codigo_seq do campo: q81_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q81_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipcalc_q81_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q81_codigo)){
         $this->erro_sql = " Campo q81_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q81_codigo = $q81_codigo; 
       }
     }
     if(($this->q81_codigo == null) || ($this->q81_codigo == "") ){ 
       $this->erro_sql = " Campo q81_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipcalc(
                                       q81_codigo 
                                      ,q81_descr 
                                      ,q81_abrev 
                                      ,q81_cadcalc 
                                      ,q81_integr 
                                      ,q81_tippro 
                                      ,q81_recexe 
                                      ,q81_qiexe 
                                      ,q81_qfexe 
                                      ,q81_valexe 
                                      ,q81_recpro 
                                      ,q81_qipro 
                                      ,q81_qfpro 
                                      ,q81_valpro 
                                      ,q81_uqtab 
                                      ,q81_uqcad 
                                      ,q81_gera 
                                      ,q81_tipo 
                                      ,q81_percprovis 
                                      ,q81_usaretido 
                                      ,q81_excedenteativ 
                       )
                values (
                                $this->q81_codigo 
                               ,'$this->q81_descr' 
                               ,'$this->q81_abrev' 
                               ,$this->q81_cadcalc 
                               ,'$this->q81_integr' 
                               ,'$this->q81_tippro' 
                               ,$this->q81_recexe 
                               ,$this->q81_qiexe 
                               ,$this->q81_qfexe 
                               ,$this->q81_valexe 
                               ,$this->q81_recpro 
                               ,$this->q81_qipro 
                               ,$this->q81_qfpro 
                               ,$this->q81_valpro 
                               ,'$this->q81_uqtab' 
                               ,'$this->q81_uqcad' 
                               ,$this->q81_gera 
                               ,$this->q81_tipo 
                               ,$this->q81_percprovis 
                               ,'$this->q81_usaretido' 
                               ,$this->q81_excedenteativ 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q81_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q81_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q81_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q81_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,335,'$this->q81_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,69,335,'','".AddSlashes(pg_result($resaco,0,'q81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,336,'','".AddSlashes(pg_result($resaco,0,'q81_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,337,'','".AddSlashes(pg_result($resaco,0,'q81_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,338,'','".AddSlashes(pg_result($resaco,0,'q81_cadcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,339,'','".AddSlashes(pg_result($resaco,0,'q81_integr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,340,'','".AddSlashes(pg_result($resaco,0,'q81_tippro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,341,'','".AddSlashes(pg_result($resaco,0,'q81_recexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,342,'','".AddSlashes(pg_result($resaco,0,'q81_qiexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,343,'','".AddSlashes(pg_result($resaco,0,'q81_qfexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,344,'','".AddSlashes(pg_result($resaco,0,'q81_valexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,345,'','".AddSlashes(pg_result($resaco,0,'q81_recpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,346,'','".AddSlashes(pg_result($resaco,0,'q81_qipro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,347,'','".AddSlashes(pg_result($resaco,0,'q81_qfpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,348,'','".AddSlashes(pg_result($resaco,0,'q81_valpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,349,'','".AddSlashes(pg_result($resaco,0,'q81_uqtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,350,'','".AddSlashes(pg_result($resaco,0,'q81_uqcad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,352,'','".AddSlashes(pg_result($resaco,0,'q81_gera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,5132,'','".AddSlashes(pg_result($resaco,0,'q81_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,7913,'','".AddSlashes(pg_result($resaco,0,'q81_percprovis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,9192,'','".AddSlashes(pg_result($resaco,0,'q81_usaretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,69,9568,'','".AddSlashes(pg_result($resaco,0,'q81_excedenteativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q81_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tipcalc set ";
     $virgula = "";
     if(trim($this->q81_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_codigo"])){ 
       $sql  .= $virgula." q81_codigo = $this->q81_codigo ";
       $virgula = ",";
       if(trim($this->q81_codigo) == null ){ 
         $this->erro_sql = " Campo codigo do tipo de calculo nao Informado.";
         $this->erro_campo = "q81_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_descr"])){ 
       $sql  .= $virgula." q81_descr = '$this->q81_descr' ";
       $virgula = ",";
       if(trim($this->q81_descr) == null ){ 
         $this->erro_sql = " Campo descricao nao Informado.";
         $this->erro_campo = "q81_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_abrev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_abrev"])){ 
       $sql  .= $virgula." q81_abrev = '$this->q81_abrev' ";
       $virgula = ",";
       if(trim($this->q81_abrev) == null ){ 
         $this->erro_sql = " Campo abreviatura da descricao pra mostrar nas consultas nao Informado.";
         $this->erro_campo = "q81_abrev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_cadcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_cadcalc"])){ 
       $sql  .= $virgula." q81_cadcalc = $this->q81_cadcalc ";
       $virgula = ",";
       if(trim($this->q81_cadcalc) == null ){ 
         $this->erro_sql = " Campo calculo a ser utilizado nao Informado.";
         $this->erro_campo = "q81_cadcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_integr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_integr"])){ 
       $sql  .= $virgula." q81_integr = '$this->q81_integr' ";
       $virgula = ",";
       if(trim($this->q81_integr) == null ){ 
         $this->erro_sql = " Campo se integral ou nao nao Informado.";
         $this->erro_campo = "q81_integr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_tippro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_tippro"])){ 
       $sql  .= $virgula." q81_tippro = '$this->q81_tippro' ";
       $virgula = ",";
       if(trim($this->q81_tippro) == null ){ 
         $this->erro_sql = " Campo tipo de proporcionalidade nao Informado.";
         $this->erro_campo = "q81_tippro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_recexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_recexe"])){ 
       $sql  .= $virgula." q81_recexe = $this->q81_recexe ";
       $virgula = ",";
       if(trim($this->q81_recexe) == null ){ 
         $this->erro_sql = " Campo receita do exercicio nao Informado.";
         $this->erro_campo = "q81_recexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_qiexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_qiexe"])){ 
       $sql  .= $virgula." q81_qiexe = $this->q81_qiexe ";
       $virgula = ",";
       if(trim($this->q81_qiexe) == null ){ 
         $this->erro_sql = " Campo quantidade inicial do exercicio nao Informado.";
         $this->erro_campo = "q81_qiexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_qfexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_qfexe"])){ 
       $sql  .= $virgula." q81_qfexe = $this->q81_qfexe ";
       $virgula = ",";
       if(trim($this->q81_qfexe) == null ){ 
         $this->erro_sql = " Campo quantidade final do exercicio nao Informado.";
         $this->erro_campo = "q81_qfexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_valexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_valexe"])){ 
       $sql  .= $virgula." q81_valexe = $this->q81_valexe ";
       $virgula = ",";
       if(trim($this->q81_valexe) == null ){ 
         $this->erro_sql = " Campo valor utilizado para o exercicio nao Informado.";
         $this->erro_campo = "q81_valexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_recpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_recpro"])){ 
       $sql  .= $virgula." q81_recpro = $this->q81_recpro ";
       $virgula = ",";
       if(trim($this->q81_recpro) == null ){ 
         $this->erro_sql = " Campo receita do proximo exercicio nao Informado.";
         $this->erro_campo = "q81_recpro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_qipro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_qipro"])){ 
       $sql  .= $virgula." q81_qipro = $this->q81_qipro ";
       $virgula = ",";
       if(trim($this->q81_qipro) == null ){ 
         $this->erro_sql = " Campo quantidade inicial do proximo exercicio nao Informado.";
         $this->erro_campo = "q81_qipro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_qfpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_qfpro"])){ 
       $sql  .= $virgula." q81_qfpro = $this->q81_qfpro ";
       $virgula = ",";
       if(trim($this->q81_qfpro) == null ){ 
         $this->erro_sql = " Campo quantidade final do proximo exercicio nao Informado.";
         $this->erro_campo = "q81_qfpro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_valpro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_valpro"])){ 
       $sql  .= $virgula." q81_valpro = $this->q81_valpro ";
       $virgula = ",";
       if(trim($this->q81_valpro) == null ){ 
         $this->erro_sql = " Campo valor utilizado para o proximo exercicio nao Informado.";
         $this->erro_campo = "q81_valpro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_uqtab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_uqtab"])){ 
       $sql  .= $virgula." q81_uqtab = '$this->q81_uqtab' ";
       $virgula = ",";
       if(trim($this->q81_uqtab) == null ){ 
         $this->erro_sql = " Campo utilizar quantidade da tabela de atividades nao Informado.";
         $this->erro_campo = "q81_uqtab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_uqcad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_uqcad"])){ 
       $sql  .= $virgula." q81_uqcad = '$this->q81_uqcad' ";
       $virgula = ",";
       if(trim($this->q81_uqcad) == null ){ 
         $this->erro_sql = " Campo utiliza quantidade do cadastro nao Informado.";
         $this->erro_campo = "q81_uqcad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_gera)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_gera"])){ 
       $sql  .= $virgula." q81_gera = $this->q81_gera ";
       $virgula = ",";
       if(trim($this->q81_gera) == null ){ 
         $this->erro_sql = " Campo tipo de geracao nao Informado.";
         $this->erro_campo = "q81_gera";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_tipo"])){ 
       $sql  .= $virgula." q81_tipo = $this->q81_tipo ";
       $virgula = ",";
       if(trim($this->q81_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de cálculo nao Informado.";
         $this->erro_campo = "q81_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_percprovis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_percprovis"])){ 
       $sql  .= $virgula." q81_percprovis = $this->q81_percprovis ";
       $virgula = ",";
       if(trim($this->q81_percprovis) == null ){ 
         $this->erro_sql = " Campo Percentual para provisorio nao Informado.";
         $this->erro_campo = "q81_percprovis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_usaretido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_usaretido"])){ 
       $sql  .= $virgula." q81_usaretido = '$this->q81_usaretido' ";
       $virgula = ",";
       if(trim($this->q81_usaretido) == null ){ 
         $this->erro_sql = " Campo Se usa essa aliquota na lista de retencoes nao Informado.";
         $this->erro_campo = "q81_usaretido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q81_excedenteativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q81_excedenteativ"])){ 
       $sql  .= $virgula." q81_excedenteativ = $this->q81_excedenteativ ";
       $virgula = ",";
       if(trim($this->q81_excedenteativ) == null ){ 
         $this->erro_sql = " Campo Excedente por atividade nao Informado.";
         $this->erro_campo = "q81_excedenteativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q81_codigo!=null){
       $sql .= " q81_codigo = $this->q81_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q81_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,335,'$this->q81_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_codigo"]))
           $resac = db_query("insert into db_acount values($acount,69,335,'".AddSlashes(pg_result($resaco,$conresaco,'q81_codigo'))."','$this->q81_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_descr"]))
           $resac = db_query("insert into db_acount values($acount,69,336,'".AddSlashes(pg_result($resaco,$conresaco,'q81_descr'))."','$this->q81_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_abrev"]))
           $resac = db_query("insert into db_acount values($acount,69,337,'".AddSlashes(pg_result($resaco,$conresaco,'q81_abrev'))."','$this->q81_abrev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_cadcalc"]))
           $resac = db_query("insert into db_acount values($acount,69,338,'".AddSlashes(pg_result($resaco,$conresaco,'q81_cadcalc'))."','$this->q81_cadcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_integr"]))
           $resac = db_query("insert into db_acount values($acount,69,339,'".AddSlashes(pg_result($resaco,$conresaco,'q81_integr'))."','$this->q81_integr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_tippro"]))
           $resac = db_query("insert into db_acount values($acount,69,340,'".AddSlashes(pg_result($resaco,$conresaco,'q81_tippro'))."','$this->q81_tippro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_recexe"]))
           $resac = db_query("insert into db_acount values($acount,69,341,'".AddSlashes(pg_result($resaco,$conresaco,'q81_recexe'))."','$this->q81_recexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_qiexe"]))
           $resac = db_query("insert into db_acount values($acount,69,342,'".AddSlashes(pg_result($resaco,$conresaco,'q81_qiexe'))."','$this->q81_qiexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_qfexe"]))
           $resac = db_query("insert into db_acount values($acount,69,343,'".AddSlashes(pg_result($resaco,$conresaco,'q81_qfexe'))."','$this->q81_qfexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_valexe"]))
           $resac = db_query("insert into db_acount values($acount,69,344,'".AddSlashes(pg_result($resaco,$conresaco,'q81_valexe'))."','$this->q81_valexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_recpro"]))
           $resac = db_query("insert into db_acount values($acount,69,345,'".AddSlashes(pg_result($resaco,$conresaco,'q81_recpro'))."','$this->q81_recpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_qipro"]))
           $resac = db_query("insert into db_acount values($acount,69,346,'".AddSlashes(pg_result($resaco,$conresaco,'q81_qipro'))."','$this->q81_qipro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_qfpro"]))
           $resac = db_query("insert into db_acount values($acount,69,347,'".AddSlashes(pg_result($resaco,$conresaco,'q81_qfpro'))."','$this->q81_qfpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_valpro"]))
           $resac = db_query("insert into db_acount values($acount,69,348,'".AddSlashes(pg_result($resaco,$conresaco,'q81_valpro'))."','$this->q81_valpro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_uqtab"]))
           $resac = db_query("insert into db_acount values($acount,69,349,'".AddSlashes(pg_result($resaco,$conresaco,'q81_uqtab'))."','$this->q81_uqtab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_uqcad"]))
           $resac = db_query("insert into db_acount values($acount,69,350,'".AddSlashes(pg_result($resaco,$conresaco,'q81_uqcad'))."','$this->q81_uqcad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_gera"]))
           $resac = db_query("insert into db_acount values($acount,69,352,'".AddSlashes(pg_result($resaco,$conresaco,'q81_gera'))."','$this->q81_gera',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_tipo"]))
           $resac = db_query("insert into db_acount values($acount,69,5132,'".AddSlashes(pg_result($resaco,$conresaco,'q81_tipo'))."','$this->q81_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_percprovis"]))
           $resac = db_query("insert into db_acount values($acount,69,7913,'".AddSlashes(pg_result($resaco,$conresaco,'q81_percprovis'))."','$this->q81_percprovis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_usaretido"]))
           $resac = db_query("insert into db_acount values($acount,69,9192,'".AddSlashes(pg_result($resaco,$conresaco,'q81_usaretido'))."','$this->q81_usaretido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q81_excedenteativ"]))
           $resac = db_query("insert into db_acount values($acount,69,9568,'".AddSlashes(pg_result($resaco,$conresaco,'q81_excedenteativ'))."','$this->q81_excedenteativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q81_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q81_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q81_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,335,'$q81_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,69,335,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,336,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,337,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_abrev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,338,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_cadcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,339,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_integr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,340,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_tippro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,341,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_recexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,342,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_qiexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,343,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_qfexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,344,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_valexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,345,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_recpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,346,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_qipro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,347,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_qfpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,348,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_valpro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,349,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_uqtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,350,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_uqcad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,352,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_gera'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,5132,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,7913,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_percprovis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,9192,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_usaretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,69,9568,'','".AddSlashes(pg_result($resaco,$iresaco,'q81_excedenteativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipcalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q81_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q81_codigo = $q81_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q81_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q81_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q81_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipcalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q81_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalc ";
     $sql .= "      inner join cadcalc  on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql .= "      inner join geradesc  on  geradesc.q89_codigo = tipcalc.q81_gera";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = tipcalc.q81_recexe";
     $sql .= "      inner join tabrec as c on c.k02_codigo = tipcalc.q81_recpro";
     $sql .= "      inner join cadvencdesc  as a on   a.q92_codigo = cadcalc.q85_codven";
     $sql .= "      inner join forcaldesc  on  forcaldesc.q87_codigo = cadcalc.q85_forcal";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = a.q92_hist";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = a.q92_tipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrecjm  as b on   b.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";

     if($dbwhere==""){
       if($q81_codigo!=null ){
         $sql2 .= " where tipcalc.q81_codigo = $q81_codigo "; 
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
   function sql_query_file ( $q81_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipcalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q81_codigo!=null ){
         $sql2 .= " where tipcalc.q81_codigo = $q81_codigo "; 
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


   function sql_query_alt ( $q81_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tipcalc ";
     $sql .= "      inner join cadcalc     on  cadcalc.q85_codigo = tipcalc.q81_cadcalc";
     $sql .= "      inner join geradesc    on  geradesc.q89_codigo = tipcalc.q81_gera";
     $sql .= "      inner join tabrec      on  tabrec.k02_codigo = tipcalc.q81_recexe";
     $sql .= "      inner join tabrec as c on c.k02_codigo = tipcalc.q81_recpro";
     $sql .= "      inner join cadvencdesc on  cadvencdesc.q92_codigo = cadcalc.q85_codven";
     $sql .= "      inner join forcaldesc  on  forcaldesc.q87_codigo = cadcalc.q85_forcal";
     $sql2 = "";
     if($dbwhere==""){
       if($q81_codigo!=null ){
         $sql2 .= " where tipcalc.q81_codigo = $q81_codigo ";
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
  
   function sql_query_virada_issqn ( $q81_codigo=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from tipcalc                                                            ";
		 $sql .= "      inner join cadcalc     on q81_cadcalc = q85_codigo                 ";
		 $sql .= "      inner join tipcalcexe  on q81_codigo  = q83_tipcalc                ";
		 $sql .= "                            and q83_anousu  = ".db_getsession('DB_anousu');
		 $sql .= "      inner join cadvencdesc on q83_codven  = q92_codigo                 ";
     
     $sql2 = "";

     if($dbwhere==""){
       if($q81_codigo!=null ){
         $sql2 .= " where tipcalc.q81_codigo = $q81_codigo "; 
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
  
  
  function sql_dados_calculo($iAtividade, $iAno) {
     
      $sCampos  = "tipcalc.q81_codigo     as tipocalculo,                       ";
      $sCampos .= "tipcalc.q81_cadcalc    as calculo,                           ";                                                      
      $sCampos .= "tipcalc.q81_descr      as tipocalculo_descricao,             ";      
      $sCampos .= "tipcalc.q81_abrev      as tipocalculo_abreviacao,            ";
      $sCampos .= "tipcalc.q81_percprovis as percentualprovisorio,              ";
      $sCampos .= "tipcalc.q81_recexe     as receitaexercicio,                  ";
      $sCampos .= "tipcalc.q81_qiexe      as quantidadeinicialexercicio,        ";
      $sCampos .= "tipcalc.q81_qfexe      as quantidadefinalexercicio,          ";
      $sCampos .= "tipcalc.q81_valexe     as valorexercicio,                    ";
      $sCampos .= "tipcalc.q81_tippro     as tipoproporcionalidade,             ";         
      $sCampos .= "tipcalc.q81_recpro     as receitaproximoexercicio,           ";
      $sCampos .= "tipcalc.q81_qipro      as quantidadeinicialproximoexercicio, ";
      $sCampos .= "tipcalc.q81_qfpro      as quantidadefinalproximoexercicio,   ";
      $sCampos .= "tipcalc.q81_valpro     as valorproximoexercicio,             ";
      $sCampos .= "tipcalc.q81_gera       as configuracaogeracao,               ";
      $sCampos .= "tipcalc.q81_integr     as integral,                          ";
      $sCampos .= "tipcalc.q81_uqtab      as utilizaquantidadeatividade,        ";
      $sCampos .= "tipcalc.q81_uqcad      as utilizamultiplicador,              ";      
      $sCampos .= "cadcalc.q85_var        as variavel,                          ";
      $sCampos .= "cadcalc.q85_perman     as permanente,                        ";
      $sCampos .= "cadcalc.q85_forcal     as formacalculo,                      ";
      $sCampos .= "cadcalc.q85_descr      as calculo_descricao,                 ";      
      
      $sCamposQuery1  = "case                                                                                      ";                                                                             
      $sCamposQuery1 .= "  when q81_tipo = 4                                                                       ";                                                                             
      $sCamposQuery1 .= "    then ( select q83_codven                                                              ";                                                                             
      $sCamposQuery1 .= "             from ativtipo ativtipoalvara                                                 ";                                                                     
      $sCamposQuery1 .= "                  inner join tipcalc    on ativtipoalvara.q80_tipcal = tipcalc.q81_codigo ";                    
      $sCamposQuery1 .= "                  left  join tipcalcexe on tipcalcexe.q83_tipcalc    = tipcalc.q81_codigo ";                    
      $sCamposQuery1 .= "                                       and tipcalcexe.q83_anousu     = {$iAno}            ";         
      $sCamposQuery1 .= "            where ativtipoalvara.q80_ativ   = ativtipo.q80_ativ                           ";                                        
      $sCamposQuery1 .= "              and ativtipoalvara.q80_tipcal = ativtipo.q80_tipcal )                       ";                                        
      $sCamposQuery1 .= "  else                                                                                    ";                                        
      $sCamposQuery1 .= "    q83_codven                                                                            ";                                        
      $sCamposQuery1 .= "end as codigovencimento                                                                   ";

      $sCamposQuery2  = "case                                                                                                                        ";
      $sCamposQuery2 .= "  when q81_tipo = 4                                                                                                         ";
      $sCamposQuery2 .= "    then ( select q83_codven                                                                                                ";
      $sCamposQuery2 .= "             from tipcalc as tipcalcalvara                                                                                  ";
      $sCamposQuery2 .= "                   left join tipcalcexe as tipcalcexealvara     on tipcalcexealvara.q83_tipcalc = tipcalc.q81_codigo        "; 
      $sCamposQuery2 .= "                                                               and tipcalcexealvara.q83_anousu  = {$iAno}                   ";
      $sCamposQuery2 .= "                  inner join cadcalc    as cadcalcalvara        on cadcalcalvara.q85_codigo     = tipcalc.q81_cadcalc       ";
      $sCamposQuery2 .= "                  inner join clasativ   as clasativalvara       on clasativalvara.q82_classe    = issportetipo.q41_codclasse"; 
      $sCamposQuery2 .= "                                                               and clasativalvara.q82_ativ      = {$iAtividade}             ";
      $sCamposQuery2 .= "             where tipcalc.q81_codigo = issportetipo.q41_codtipcalc )                                                       ";
      $sCamposQuery2 .= "  else                                                                                                                      ";
      $sCamposQuery2 .= "    q83_codven                                                                                                              ";
      $sCamposQuery2 .= "end as codigovencimento";

      $sSqlTipoCalculo  = "select distinct                                                                        ";
      $sSqlTipoCalculo .= $sCampos.$sCamposQuery1;
      $sSqlTipoCalculo .= "  from ativtipo                                                                        ";
      $sSqlTipoCalculo .= "       inner join tipcalc     on q80_tipcal  = q81_codigo                              ";
      $sSqlTipoCalculo .= "        left join tipcalcexe  on q83_tipcalc = q81_codigo                              ";
      $sSqlTipoCalculo .= "                             and q83_anousu  = {$iAno}                                 ";
      $sSqlTipoCalculo .= "       inner join cadcalc     on cadcalc.q85_codigo = tipcalc.q81_cadcalc              ";
      $sSqlTipoCalculo .= " where q81_tipo in (1,4,5)                                                             ";
      $sSqlTipoCalculo .= "   and q80_ativ = {$iAtividade}                                                        ";
      $sSqlTipoCalculo .= "                                                                                       ";
      $sSqlTipoCalculo .= " union                                                                                 ";
      $sSqlTipoCalculo .= "                                                                                       ";
      $sSqlTipoCalculo .= "select distinct                                                                        "; 
      $sSqlTipoCalculo .= $sCampos.$sCamposQuery2;
      $sSqlTipoCalculo .= "  from issportetipo                                                                    ";
      $sSqlTipoCalculo .= "       inner join tipcalc      on tipcalc.q81_codigo     = issportetipo.q41_codtipcalc ";  
      $sSqlTipoCalculo .= "       left  join tipcalcexe   on tipcalcexe.q83_tipcalc = tipcalc.q81_codigo          ";
      $sSqlTipoCalculo .= "                              and tipcalcexe.q83_anousu  = {$iAno}                     ";
      $sSqlTipoCalculo .= "       inner join cadcalc      on cadcalc.q85_codigo     = tipcalc.q81_cadcalc         ";
      $sSqlTipoCalculo .= "       inner join clasativ     on clasativ.q82_classe    = issportetipo.q41_codclasse  ";
      $sSqlTipoCalculo .= " where tipcalc.q81_tipo in (1,4,5)                                                     ";
      $sSqlTipoCalculo .= "   and clasativ.q82_ativ = {$iAtividade}                                               ";      
    
      return $sSqlTipoCalculo;
  }  
}
?>